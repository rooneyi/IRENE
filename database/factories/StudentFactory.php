<?php
namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        // Sections et classes associées
        $sections = [
            'Maternelle' => [
                'id' => 1,
                'classes' => ['1ère maternelle', '2ème maternelle', '3ème maternelle'],
                'montant' => 40,
            ],
            'Primaire' => [
                'id' => 2,
                'classes' => ['1ère primaire', '2ème primaire', '3ème primaire', '4ème primaire', '5ème primaire', '6ème primaire'],
                'montant' => 45,
            ],
            'Secondaire général' => [
                'id' => 3,
                'classes' => ['1ère secondaire', '2ème secondaire', '3ème secondaire', '4ème secondaire', '5ème secondaire', '6ème secondaire'],
                'montant' => 65,
            ],
            'Technique' => [
                'id' => 4,
                'classes' => ['1ère technique', '2ème technique', '3ème technique', '4ème technique', '5ème technique', '6ème technique'],
                'montant' => 95,
            ],
        ];
        $sectionName = $this->faker->randomElement(array_keys($sections));
        $section = $sections[$sectionName];
        $classe = $this->faker->randomElement($section['classes']);
        $mois = 12;
        // Noms congolais réalistes
        $noms = ['Mwamba', 'Kabasele', 'Mutombo', 'Tshibola', 'Ilunga', 'Kasongo', 'Kabeya', 'Mbuyi', 'Lutumba', 'Makiese'];
        $prenoms = ['Patrick', 'Chantal', 'Junior', 'Prisca', 'Grace', 'Fabrice', 'Esther', 'Blaise', 'Sarah', 'Emmanuel'];
        $post_noms = ['Ilunga', 'Mbuyi', 'Kasongo', 'Kabeya', 'Lutumba', 'Makiese', 'Kalala', 'Ngoy', 'Mundele', 'Banza'];
        return [
            'nom' => $this->faker->randomElement($noms),
            'prenom' => $this->faker->randomElement($prenoms),
            'post_nom' => $this->faker->randomElement($post_noms),
            'matricule' => strtoupper($this->faker->unique()->bothify('MAT2025####')),
            'classe' => $classe,
            'section_id' => $section['id'],
            'date_naissance' => $this->faker->date('Y-m-d', '-10 years'),
            'sexe' => $this->faker->randomElement(['M', 'F']),
            'adresse' => $this->faker->address(),
            'tuteur' => $this->faker->randomElement($noms) . ' ' . $this->faker->randomElement($prenoms),
            'telephone_tuteur' => '099' . $this->faker->numberBetween(1000000, 9999999),
            'total_a_payer' => $section['montant'] * $mois,
            'mois_repartition' => $mois,
        ];
    }
}
