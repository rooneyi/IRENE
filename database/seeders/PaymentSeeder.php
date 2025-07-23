<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Student;
use App\Models\FeeType;
use App\Models\User;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::all();
        $feeTypes = FeeType::all();
        $agents = User::all();

        foreach ($students as $student) {
            foreach ($feeTypes as $feeType) {
                Payment::create([
                    'eleve_id' => $student->id,
                    'fee_type_id' => $feeType->id,
                    'montant' => $feeType->montant_par_defaut ?? 10000,
                    'date_paiement' => now()->subDays(rand(0, 60)),
                    'statut' => 'Payé',
                    'agent_encaisseur' => $agents->random()->id,
                    'numero_recu' => uniqid('REC'),
                    'remarque' => null,
                ]);
            }
        }
    }
}

