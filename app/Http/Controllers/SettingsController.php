<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Payment;
use App\Models\Setting;

class SettingsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Accès refusé. Seul l\'administrateur peut accéder aux paramètres.');
        }
        // Affichage de la page des paramètres système
        $totalAPayer = \App\Models\Student::query()->value('total_a_payer');
        $moisRepartition = \App\Models\Student::query()->value('mois_repartition');
        $moisEtudes = Setting::where('key', 'mois_etudes')->value('value') ?? [];
        return view('settings.index', compact('totalAPayer', 'moisRepartition', 'moisEtudes'));
    }

    public function backup(Request $request)
    {
        // Sauvegarde de la base de données dans storage/app/backup
        $filename = 'backup_' . date('Ymd_His') . '.sql';
        $path = storage_path('app/backup');
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        $command = sprintf(
            'mysqldump -u%s -p%s %s > %s',
            escapeshellarg(env('DB_USERNAME')),
            escapeshellarg(env('DB_PASSWORD')),
            escapeshellarg(env('DB_DATABASE')),
            escapeshellarg($path . '/' . $filename)
        );
        $output = null;
        $result = null;
        @exec($command, $output, $result);
        if ($result === 0) {
            return back()->with('success', 'Sauvegarde effectuée avec succès.');
        } else {
            return back()->with('error', 'Erreur lors de la sauvegarde.');
        }
    }

    public function configurePrinter(Request $request)
    {
        // Enregistrer une configuration imprimante fictive dans un fichier JSON
        $config = [
            'printer_name' => 'ThermalPrinter-01',
            'paper_size' => '80mm',
            'updated_at' => now(),
        ];
        Storage::disk('local')->put('printer_config.json', json_encode($config));
        return back()->with('success', 'Imprimante configurée.');
    }

    public function archive(Request $request)
    {
        // Archiver les paiements de l’année précédente dans un fichier JSON
        $year = date('Y') - 1;
        $payments = Payment::whereYear('created_at', $year)->get();
        if ($payments->count() > 0) {
            $archivePath = storage_path('app/archive');
            if (!is_dir($archivePath)) {
                mkdir($archivePath, 0777, true);
            }
            $file = $archivePath . "/paiements_$year.json";
            file_put_contents($file, $payments->toJson());
            return back()->with('success', 'Archivage effectué avec succès.');
        } else {
            return back()->with('success', 'Aucun paiement à archiver pour ' . $year . '.');
        }
    }

    public function updateFees(Request $request)
    {
        $request->validate([
            'mois_repartition' => 'required|integer|min:1|max:12',
            'mois_etudes' => 'required|array|min:1',
            'sections' => 'required|array|min:1',
            'sections.*.name' => 'required|string',
            'sections.*.montant' => 'required|numeric|min:0',
        ]);

        Setting::updateOrCreate(
            ['key' => 'mois_repartition'],
            ['value' => $request->mois_repartition]
        );
        Setting::updateOrCreate(
            ['key' => 'mois_etudes'],
            ['value' => json_encode($request->mois_etudes)]
        );
        Setting::updateOrCreate(
            ['key' => 'sections'],
            ['value' => json_encode($request->sections)]
        );

        return back()->with('success', 'Configuration des frais mise à jour avec succès.');
    }
}
