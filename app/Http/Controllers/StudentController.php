<?php
namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::orderBy('nom')->paginate(15);
        return view('students.index', compact('students'));
    }

    public function create()
    {
        $sections = \App\Models\FeeType::all();
        return view('students.create', compact('sections'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'post_nom' => 'required',
            'classe' => 'required',
            'sexe' => 'required',
            'date_naissance' => 'required',
            'telephone_tuteur' => 'required',
            'adresse' => 'required',
            'section_id' => 'required|exists:fee_types,id',
        ]);

        // Définir le montant selon la section choisie
        $montants = [
            'maternelle' => 40,
            'primaire' => 45,
            'secondaire_generale' => 65,
            'technique' => 95,
        ];

        // Génération du matricule
        $lastId = \App\Models\Student::max('id') + 1;
        $matricule = strtoupper(substr($request->nom,0,1))
            .strtoupper(substr($request->prenom,0,1))
            .date('Y')
            .str_pad($lastId, 4, '0', STR_PAD_LEFT);
        $student = new \App\Models\Student();
        $student->nom = $request->nom;
        $student->prenom = $request->prenom;
        $student->post_nom = $request->post_nom;
        $student->matricule = $matricule;
        $student->classe = $request->classe;
        $student->section_id = $request->section_id;
        $student->sexe = $request->sexe;
        $student->date_naissance = $request->date_naissance;
        $student->telephone_tuteur = $request->telephone_tuteur;
        $student->adresse = $request->adresse;
        $student->total_a_payer = $montants[$request->section_id] ?? 0;
        $student->save();

        return redirect()->route('students.index')->with('success', 'Élève ajouté avec succès !');
    }

    public function edit(Student $student)
    {
        $sections = \App\Models\FeeType::all();
        return view('students.edit', compact('student', 'sections'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'post_nom' => 'required',
            'matricule' => 'required|unique:students,matricule,'.$student->id,
            'classe' => 'required',
            'section_id' => 'required',
        ]);
        $montants = [
            'maternelle' => 40,
            'primaire' => 45,
            'secondaire_generale' => 65,
            'technique' => 95,
        ];
        $student->fill($request->all());
        $student->total_a_payer = $montants[$request->section_id] ?? 0;
        $student->save();
        return redirect()->route('students.index')->with('success', 'Élève modifié avec succès.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:csv,txt',
        ]);
        $file = $request->file('import_file');
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle);
        $header = array_map('strtolower', $header);
        $required = ['nom','prenom','classe','sexe','date_naissance','telephone','adresse'];
        foreach ($required as $field) {
            if (!in_array($field, $header)) {
                return back()->with('error', "Le champ '$field' est manquant dans le fichier.");
            }
        }
        while (($row = fgetcsv($handle)) !== false) {
            $rowData = array_combine($header, $row);
            if (empty($rowData['nom']) || empty($rowData['prenom'])) continue;
            Student::updateOrCreate([
                'nom' => $rowData['nom'],
                'prenom' => $rowData['prenom'],
                'classe' => $rowData['classe'],
            ], [
                'sexe' => $rowData['sexe'],
                'date_naissance' => $rowData['date_naissance'],
                'telephone' => $rowData['telephone'],
                'adresse' => $rowData['adresse'],
                'statut' => $rowData['statut'] ?? 'Actif',
            ]);
        }
        fclose($handle);
        return back()->with('success', 'Importation terminée !');
    }

    public function export(Request $request)
    {
        if (!auth()->check()) {
            abort(403, 'Non autorisé');
        }
        $students = Student::all(['nom','prenom','classe','sexe','date_naissance','telephone','adresse','statut']);
        if ($students->isEmpty()) {
            return redirect()->route('students.index')->with('error', 'Aucun élève à exporter.');
        }
        $filename = 'eleves_'.date('Ymd_His').'.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Cache-Control' => 'no-store, no-cache',
        ];
        $columns = ['Nom','Prénom','Classe','Sexe','Date_naissance','Téléphone','Adresse','Statut'];
        $callback = function() use ($students, $columns) {
            $file = fopen('php://output', 'w');
            fwrite($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $columns);
            foreach ($students as $student) {
                fputcsv($file, [
                    $student->nom,
                    $student->prenom,
                    $student->classe,
                    $student->sexe,
                    $student->date_naissance,
                    $student->telephone,
                    $student->adresse,
                    $student->statut,
                ]);
            }
            fclose($file);
        };
        // On retourne le CSV, sinon on redirige si vide
        return response()->stream($callback, 200, $headers);
    }
    public function show(Student $student)
    {
        // Récupérer les paiements de l'élève
        $paiements = $student->payments()->with('feeType')->orderByDesc('date_paiement')->get();
        return view('students.show', compact('student', 'paiements'));
    }
    public function moisNonPayes($id)
    {
        $student = \App\Models\Student::findOrFail($id);
        $moisEtudes = \App\Models\Setting::where('key', 'mois_etudes')->value('value') ?? [];
        // Correction : décoder si c'est une chaîne JSON
        if (is_string($moisEtudes)) {
            $moisEtudes = json_decode($moisEtudes, true) ?? [];
        }
        $moisPayes = [];
        foreach ($student->payments as $payment) {
            if ($payment->mois_payes) {
                $moisPayes = array_merge($moisPayes, json_decode($payment->mois_payes, true));
            }
        }
        $moisNonPayes = array_values(array_diff($moisEtudes, $moisPayes));
        return response()->json($moisNonPayes);
    }
}
