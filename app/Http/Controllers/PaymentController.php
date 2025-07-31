<?php
namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use App\Models\FeeType;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        if (!$user || !in_array($user->role, ['admin', 'caissier'])) {
            abort(403, 'Accès refusé.');
        }
        // Récupérer toutes les classes distinctes
        $classes = Student::select('classe')->distinct()->pluck('classe');
        $feeTypes = FeeType::all();

        $query = Payment::with(['student', 'feeType']);

        // Filtres
        if ($request->filled('eleve')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('nom', 'like', '%'.$request->eleve.'%')
                  ->orWhere('prenom', 'like', '%'.$request->eleve.'%');
            });
        }
        if ($request->filled('classe')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('classe', $request->classe);
            });
        }
        if ($request->filled('mois')) {
            $query->whereMonth('date_paiement', substr($request->mois, 5, 2))
                  ->whereYear('date_paiement', substr($request->mois, 0, 4));
        }

        $payments = $query->orderBy('date_paiement', 'desc')->paginate(10);

        return view('payments.index', compact('payments', 'classes', 'feeTypes'));
    }

    public function create()
    {
        $user = auth()->user();
        if (!$user || $user->role !== 'caissier') {
            abort(403, 'Accès refusé. Seul le caissier peut effectuer cette action.');
        }
        $students = \App\Models\Student::orderBy('nom')->get();
        $sections = \App\Models\FeeType::all(['id', 'nom', 'montant_par_defaut']);
        return view('payments.create', compact('students', 'sections'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user || $user->role !== 'caissier') {
            abort(403, 'Accès refusé. Seul le caissier peut effectuer cette action.');
        }
        $request->validate([
            'eleve_id' => 'required|exists:students,id',
            'montant' => 'required|numeric|min:0',
            'devise' => 'required|in:FC,USD',
            'date_paiement' => 'required|date',
            'statut' => 'required|in:Payé,En attente,Incomplet',
            'remarque' => 'nullable|string',
        ]);

        // Vérifier doublon (même élève, type de frais, mois)
        $doublon = Payment::where('eleve_id', $request->eleve_id)
            ->whereMonth('date_paiement', date('m', strtotime($request->date_paiement)))
            ->whereYear('date_paiement', date('Y', strtotime($request->date_paiement)))
            ->first();
        if ($doublon) {
            return back()->withErrors(['doublon' => 'Un paiement similaire existe déjà pour cet élève, ce type de frais et ce mois.']);
        }

        $payment = Payment::create([
            'eleve_id' => $request->eleve_id,
            'montant' => $request->montant,
            'devise' => $request->devise,
            'date_paiement' => $request->date_paiement,
            'statut' => $request->statut,
            'agent_encaisseur' => auth()->id(),
            'numero_recu' => uniqid('REC'),
            'remarque' => $request->remarque,
            'mois_payes' => $request->filled('mois_payes') ? $request->mois_payes : null,
        ]);

        // Si aucun mois n'est envoyé, fallback sur le calcul automatique (sécurité)
        if (!$payment->mois_payes) {
            $student = Student::findOrFail($request->eleve_id);
            $moisEtudes = \App\Models\Setting::where('key', 'mois_etudes')->value('value') ?? [];
            $moisPayes = [];
            foreach ($student->payments as $p) {
                if ($p->mois_payes) {
                    $moisPayes = array_merge($moisPayes, json_decode($p->mois_payes, true));
                }
            }
            $moisNonPayes = array_values(array_diff($moisEtudes, $moisPayes));
            $montantMensuelFC = $student->mois_repartition > 0 ? $student->total_a_payer / $student->mois_repartition : 0;
            if ($request->devise === 'USD') {
                $montantMensuel = $montantMensuelFC / 2800;
            } else {
                $montantMensuel = $montantMensuelFC;
            }
            $nbMois = ($montantMensuel > 0) ? floor($request->montant / $montantMensuel) : 0;
            $moisPourCePaiement = array_slice($moisNonPayes, 0, $nbMois);
            $payment->mois_payes = json_encode($moisPourCePaiement);
            $payment->save();
        }

        // Journaliser l'action
        \App\Models\Log::create([
            'user_id' => auth()->id(),
            'action' => 'Ajout paiement',
            'description' => 'Ajout du paiement ID '.$payment->id.' pour élève ID '.$request->eleve_id,
            'ip' => $request->ip(),
        ]);

        return redirect()->route('payments.show', $payment->id)->with('success', 'Paiement enregistré avec succès. Vous pouvez imprimer le reçu.');
    }

    public function show(Payment $payment)
    {
        $user = auth()->user();
        if (!$user || !in_array($user->role, ['admin', 'caissier'])) {
            abort(403, 'Accès refusé.');
        }
        $payment->load(['student', 'agent']);
        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        $user = auth()->user();
        if (!$user || $user->role !== 'caissier') {
            abort(403, 'Accès refusé. Seul le caissier peut effectuer cette action.');
        }
        $students = \App\Models\Student::orderBy('nom')->get();
        $sections = \App\Models\FeeType::all(['id', 'nom', 'montant_par_defaut']);
        return view('payments.edit', compact('payment', 'students', 'sections'));
    }

    public function update(Request $request, Payment $payment)
    {
        $user = auth()->user();
        if (!$user || $user->role !== 'caissier') {
            abort(403, 'Accès refusé. Seul le caissier peut effectuer cette action.');
        }
        $request->validate([
            'eleve_id' => 'required|exists:students,id',
            'montant' => 'required|numeric|min:0',
            'devise' => 'required|in:FC,USD',
            'date_paiement' => 'required|date',
            'statut' => 'required|in:Payé,En attente,Incomplet',
            'remarque' => 'nullable|string',
        ]);
        $payment->update([
            'eleve_id' => $request->eleve_id,
            'montant' => $request->montant,
            'devise' => $request->devise,
            'date_paiement' => $request->date_paiement,
            'statut' => $request->statut,
            'remarque' => $request->remarque,
            'mois_payes' => $request->filled('mois_payes') ? $request->mois_payes : null,
        ]);
        // Si aucun mois n'est envoyé, fallback sur le calcul automatique (sécurité)
        if (!$payment->mois_payes) {
            $student = Student::findOrFail($request->eleve_id);
            $moisEtudes = \App\Models\Setting::where('key', 'mois_etudes')->value('value') ?? [];
            $moisPayes = [];
            foreach ($student->payments as $p) {
                if ($p->mois_payes) {
                    $moisPayes = array_merge($moisPayes, json_decode($p->mois_payes, true));
                }
            }
            $moisNonPayes = array_values(array_diff($moisEtudes, $moisPayes));
            $montantMensuelFC = $student->mois_repartition > 0 ? $student->total_a_payer / $student->mois_repartition : 0;
            if ($request->devise === 'USD') {
                $montantMensuel = $montantMensuelFC / 2800;
            } else {
                $montantMensuel = $montantMensuelFC;
            }
            $nbMois = ($montantMensuel > 0) ? floor($request->montant / $montantMensuel) : 0;
            $moisPourCePaiement = array_slice($moisNonPayes, 0, $nbMois);
            $payment->mois_payes = json_encode($moisPourCePaiement);
            $payment->save();
        }
        // Journaliser l'action
        \App\Models\Log::create([
            'user_id' => auth()->id(),
            'action' => 'Modification paiement',
            'description' => 'Modification du paiement ID '.$payment->id,
            'ip' => $request->ip(),
        ]);
        return redirect()->route('payments.index')->with('success', 'Paiement modifié avec succès.');
    }

    public function destroy(Payment $payment)
    {
        $user = auth()->user();
        if (!$user || $user->role !== 'caissier') {
            abort(403, 'Accès refusé. Seul le caissier peut effectuer cette action.');
        }
        $payment->delete();
        // Journaliser l'action
        \App\Models\Log::create([
            'user_id' => auth()->id(),
            'action' => 'Suppression paiement',
            'description' => 'Suppression du paiement ID '.$payment->id,
            'ip' => request()->ip(),
        ]);
        return redirect()->route('payments.index')->with('success', 'Paiement supprimé.');
    }

    public function receipt(Payment $payment)
    {
        $user = auth()->user();
        if (!$user || !in_array($user->role, ['admin', 'caissier'])) {
            abort(403, 'Accès refusé.');
        }
        $payment->load(['student', 'agent']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('payments.show', compact('payment'));
        return $pdf->download('recu_paiement_'.$payment->id.'.pdf');
    }

    public function showReceipt(Payment $payment)
    {
        $user = auth()->user();
        if (!$user || !in_array($user->role, ['admin', 'caissier'])) {
            abort(403, 'Accès refusé.');
        }
        $payment->load(['student', 'agent']);
        return view('payments.receipt', compact('payment'));
    }
}
