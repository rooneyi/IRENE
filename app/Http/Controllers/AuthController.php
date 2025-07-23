<?php

namespace App\Http\Controllers;

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
        $role = $request->input('role');
        $credentials['role'] = $role;
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('user.dashboard');
            }
        }
        return back()->withErrors(['email' => 'Identifiants invalides'])->withInput();
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function adminDashboard()
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('user.dashboard');
        }

        // Statistiques globales
        $totalPaiements = \App\Models\Payment::count();
        $tempsMoyenTraitement = \App\Models\Payment::avg('created_at') ? 2 : 0; // À remplacer par une vraie logique si tu as un champ de durée
        $tauxErreurs = 0; // À calculer selon ta logique (ex : nombre de logs d'erreur / total paiements)
        $tauxRecouvrement = \App\Models\Payment::where('statut', 'Payé')->count() > 0 ?
            round(\App\Models\Payment::where('statut', 'Payé')->count() / max(1, $totalPaiements) * 100, 2) : 0;
        $satisfactionParentale = 4.5; // À remplacer par une vraie moyenne si tu as un système d'avis

        // Graphique : paiements par mois
        $paiementsParMois = \App\Models\Payment::selectRaw('DATE_FORMAT(date_paiement, "%Y-%m") as mois, COUNT(*) as total')
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

        return view('dashboard', compact(
            'totalPaiements',
            'tempsMoyenTraitement',
            'tauxErreurs',
            'tauxRecouvrement',
            'satisfactionParentale',
            'graphData',
            'alertes'
        ));
    }

    public function userDashboard()
    {
        return view('user');
    }
}
