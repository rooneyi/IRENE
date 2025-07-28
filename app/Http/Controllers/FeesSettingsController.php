<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Setting;
use App\Models\FeeType;

class FeesSettingsController extends Controller
{
    public function edit()
    {
        $totalAPayer = Student::query()->value('total_a_payer');
        $moisRepartition = Student::query()->value('mois_repartition');
        $fraisInscription = Setting::where('key', 'frais_inscription')->value('value');
        $sections = FeeType::all();
        return view('settings.fees', compact('totalAPayer', 'moisRepartition', 'fraisInscription', 'sections'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'frais_inscription' => 'required|numeric|min:0',
            'total_a_payer' => 'required|numeric|min:0',
            'mois_repartition' => 'required|integer|min:1|max:12',
            'sections' => 'array',
            'sections.*.name' => 'required|string',
            'sections.*.montant' => 'required|numeric|min:0',
        ]);
        // Met à jour pour tous les élèves
        Student::query()->update([
            'total_a_payer' => $request->total_a_payer,
            'mois_repartition' => $request->mois_repartition,
        ]);
        // Met à jour le frais d'inscription dans settings
        Setting::updateOrCreate(
            ['key' => 'frais_inscription'],
            ['value' => $request->frais_inscription]
        );
        // Suppression des anciennes sections qui ne sont plus dans le formulaire
        $nomsForm = collect($request->sections)->pluck('name')->toArray();
        FeeType::whereNotIn('nom', $nomsForm)->delete();
        // Mise à jour ou création des sections
        if ($request->has('sections')) {
            foreach ($request->sections as $section) {
                FeeType::updateOrCreate(
                    ['nom' => $section['name']],
                    [
                        'description' => $section['description'] ?? '',
                        'montant_par_defaut' => $section['montant'],
                    ]
                );
            }
        }
        return redirect()->route('settings.fees.edit')->with('success', 'Configuration mise à jour avec succès.');
    }
}
