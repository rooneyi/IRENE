<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FeeType;

class FeeTypeSeeder extends Seeder
{
    public function run(): void
    {
        FeeType::insert([
            [
                'nom' => 'Inscription',
                'description' => 'Frais d’inscription annuelle',
                'montant_par_defaut' => 25000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Scolarité',
                'description' => 'Frais de scolarité mensuels',
                'montant_par_defaut' => 15000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Autres',
                'description' => 'Autres frais divers',
                'montant_par_defaut' => 5000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

