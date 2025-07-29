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
        // Enregistre aussi total_a_payer et mois_repartition dans settings
        Setting::updateOrCreate(
            ['key' => 'total_a_payer'],
            ['value' => $request->total_a_payer]
        );
        Setting::updateOrCreate(
            ['key' => 'mois_repartition'],
            ['value' => $request->mois_repartition]
        );
        // Suppression des sections supprimées côté front
        $idsForm = collect($request->sections)->pluck('id')->filter()->toArray();
        if (!empty($idsForm)) {
            FeeType::whereNotIn('id', $idsForm)->delete();
        }
        // Mise à jour ou création des sections
        if ($request->has('sections')) {
            foreach ($request->sections as $section) {
                if (!empty($section['id'])) {
                    // Modification
                    FeeType::where('id', $section['id'])->update([
                        'nom' => $section['name'],
                        'montant_par_defaut' => $section['montant'],
                    ]);
                } else {
                    // Création
                    FeeType::create([
                        'nom' => $section['name'],
                        'montant_par_defaut' => $section['montant'],
                    ]);
                }
            }
        }
        return redirect()->route('settings.fees.edit')->with('success', 'Configuration mise à jour avec succès.');
    }
}
