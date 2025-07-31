<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'caissier') {
                return redirect()->route('cashier.dashboard');
            } else {
                return redirect()->route('user.dashboard');
            }
        }
        return back()->withErrors(['email' => 'Email ou mot de passe Incorrecte'])->withInput();
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function adminDashboard()
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Accès refusé. Seul l\'administrateur peut accéder au tableau de bord.');
        }

        // Statistiques globales
        $nbEleves = \App\Models\Student::count();
        $nbPaiements = \App\Models\Payment::count();
        $montantTotal = \App\Models\Payment::where('statut', 'Payé')->sum('montant');
        $nbClasses = \App\Models\Student::distinct('classe')->count('classe');

        // Graphique : paiements par mois
        $paiementsParMois = Payment::selectRaw('DATE_FORMAT(date_paiement, "%Y-%m") as mois, COUNT(*) as total')
            ->groupBy('mois')
            ->orderBy('mois')
            ->pluck('total', 'mois');
        $graphData = [
            'labels' => $paiementsParMois->keys(),
            'datasets' => [[
                'label' => 'Paiements',
                'data' => $paiementsParMois->values(),
                'backgroundColor' => '#2563eb',
            ]]
        ];

        // Alertes (exemple : paiements en attente)
        $alertes = [];
        $enAttente = \App\Models\Payment::where('statut', 'En attente')->count();
        if ($enAttente > 0) {
            $alertes[] = "$enAttente paiement(s) en attente de validation.";
        }
        $incomplets = \App\Models\Payment::where('statut', 'Incomplet')->count();
        if ($incomplets > 0) {
            $alertes[] = "$incomplets paiement(s) incomplet(s).";
        }

        // Récupérer le montant total à payer et la répartition (même pour tous les élèves)
        $totalAPayer = \App\Models\Student::query()->value('total_a_payer');
        $moisRepartition = \App\Models\Student::query()->value('mois_repartition');
        // Récapitulatif par élève : total à payer, payé, reste, mois couverts
        $eleves = \App\Models\Student::with('payments')->get();
        // Synthèse des mois payés et restants par élève
        $moisEtudes = \App\Models\Setting::where('key', 'mois_etudes')->value('value') ?? [];
        if (!is_array($moisEtudes)) {
            $moisEtudes = json_decode($moisEtudes, true) ?: [];
        }
        $recapPaiementEleves = $eleves->map(function($eleve) use ($totalAPayer, $moisRepartition, $moisEtudes) {
            $totalPaye = $eleve->payments()->where('statut', 'Payé')->sum('montant');
            $resteAPayer = max(0, $totalAPayer - $totalPaye);
            $moisCouverts = $totalAPayer > 0 ? floor($totalPaye / ($totalAPayer / max(1, $moisRepartition))) : 0;
            $moisRestants = max(0, $moisRepartition - $moisCouverts);
            // Calcul des mois payés et restants
            $moisPayes = [];
            foreach ($eleve->payments as $p) {
                if ($p->mois_payes) {
                    $moisPayes = array_merge($moisPayes, json_decode($p->mois_payes, true));
                }
            }
            $moisPayes = array_unique($moisPayes);
            $moisRestantsList = array_values(array_diff($moisEtudes, $moisPayes));
            return [
                'nom' => $eleve->nom,
                'prenom' => $eleve->prenom,
                'classe' => $eleve->classe,
                'total_a_payer' => $totalAPayer,
                'total_paye' => $totalPaye,
                'reste_a_payer' => $resteAPayer,
                'mois_couverts' => $moisCouverts,
                'mois_restants' => $moisRestants,
                'mois_payes' => $moisPayes,
                'mois_restants_list' => $moisRestantsList,
            ];
        });

        return view('dashboard', compact(
            'nbEleves',
            'nbPaiements',
            'montantTotal',
            'nbClasses',
            'graphData',
            'alertes',
            'recapPaiementEleves',
            'totalAPayer',
            'moisRepartition'
        ));
    }

    public function caissierDashboard()
    {
        if (!auth()->check() || auth()->user()->role !== 'caissier') {
            abort(403, 'Accès refusé. Seul le caissier peut accéder à ce tableau de bord.');
        }
        // Nombre d'élèves ayant payé par classe
        $parClasse = \App\Models\Student::select('classe')
            ->withCount(['payments as paiements_payes_count' => function($q) {
                $q->where('statut', 'Payé');
            }])
            ->get()
            ->groupBy('classe')
            ->map(function($eleves) {
                return $eleves->sum('paiements_payes_count');
            });
        // Nombre d'élèves ayant payé par section
        $parSection = \App\Models\Student::select('section_id')
            ->withCount(['payments as paiements_payes_count' => function($q) {
                $q->where('statut', 'Payé');
            }])
            ->get()
            ->groupBy('section_id')
            ->map(function($eleves) {
                return $eleves->sum('paiements_payes_count');
            });
        // Récupérer les noms des sections
        $sections = \App\Models\FeeType::pluck('nom', 'id');
        return view('dashboard_caissier', compact('parClasse', 'parSection', 'sections'));
    }

    public function userDashboard()
    {
        if (!auth()->check() || auth()->user()->role !== 'user') {
            abort(403, 'Accès refusé. Seuls les utilisateurs peuvent accéder à ce tableau de bord.');
        }
        return view('dashboard_user');
    }
}
