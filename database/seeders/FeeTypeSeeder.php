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
                'nom' => 'Maternelle',
                'description' => 'Section maternelle',
                'montant_par_defaut' => 40,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Primaire',
                'description' => 'Section primaire',
                'montant_par_defaut' => 45,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Secondaire général',
                'description' => 'Section secondaire générale',
                'montant_par_defaut' => 65,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Technique',
                'description' => 'Section technique',
                'montant_par_defaut' => 95,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

