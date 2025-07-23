<?php
namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use App\Models\FeeType;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
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
        if ($request->filled('type_frais')) {
            $query->where('fee_type_id', $request->type_frais);
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
        $students = \App\Models\Student::orderBy('nom')->get();
        $feeTypes = \App\Models\FeeType::all();
        return view('payments.create', compact('students', 'feeTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'eleve_id' => 'required|exists:students,id',
            'fee_type_id' => 'required|exists:fee_types,id',
            'montant' => 'required|numeric|min:0',
            'date_paiement' => 'required|date',
            'statut' => 'required|in:Payé,En attente,Incomplet',
            'remarque' => 'nullable|string',
        ]);

        // Vérifier doublon (même élève, type de frais, mois)
        $doublon = Payment::where('eleve_id', $request->eleve_id)
            ->where('fee_type_id', $request->fee_type_id)
            ->whereMonth('date_paiement', date('m', strtotime($request->date_paiement)))
            ->whereYear('date_paiement', date('Y', strtotime($request->date_paiement)))
            ->first();
        if ($doublon) {
            return back()->withErrors(['doublon' => 'Un paiement similaire existe déjà pour cet élève, ce type de frais et ce mois.']);
        }

        $payment = Payment::create([
            'eleve_id' => $request->eleve_id,
            'fee_type_id' => $request->fee_type_id,
            'montant' => $request->montant,
            'date_paiement' => $request->date_paiement,
            'statut' => $request->statut,
            'agent_encaisseur' => auth()->id(),
            'numero_recu' => uniqid('REC'),
            'remarque' => $request->remarque,
        ]);

        // Journaliser l'action
        \App\Models\Log::create([
            'user_id' => auth()->id(),
            'action' => 'Ajout paiement',
            'description' => 'Ajout du paiement ID '.$payment->id.' pour élève ID '.$request->eleve_id,
            'ip' => $request->ip(),
        ]);

        return redirect()->route('payments.index')->with('success', 'Paiement enregistré avec succès.');
    }

    public function show(Payment $payment)
    {
        $payment->load(['student', 'feeType', 'agent']);
        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        $students = \App\Models\Student::orderBy('nom')->get();
        $feeTypes = \App\Models\FeeType::all();
        return view('payments.edit', compact('payment', 'students', 'feeTypes'));
    }

    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'eleve_id' => 'required|exists:students,id',
            'fee_type_id' => 'required|exists:fee_types,id',
            'montant' => 'required|numeric|min:0',
            'date_paiement' => 'required|date',
            'statut' => 'required|in:Payé,En attente,Incomplet',
            'remarque' => 'nullable|string',
        ]);
        $payment->update([
            'eleve_id' => $request->eleve_id,
            'fee_type_id' => $request->fee_type_id,
            'montant' => $request->montant,
            'date_paiement' => $request->date_paiement,
            'statut' => $request->statut,
            'remarque' => $request->remarque,
        ]);
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
}
