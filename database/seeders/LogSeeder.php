<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Log;
use App\Models\User;

class LogSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        foreach ($users as $user) {
            Log::create([
                'user_id' => $user->id,
                'action' => 'Connexion',
                'description' => 'Connexion réussie à l’interface d’administration',
                'ip' => '127.0.0.1',
            ]);
            Log::create([
                'user_id' => $user->id,
                'action' => 'Ajout paiement',
                'description' => 'Ajout d’un paiement test',
                'ip' => '127.0.0.1',
            ]);
        }
    }
}

