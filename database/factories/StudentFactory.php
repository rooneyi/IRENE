<?php
namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        $sections = [
            1 => 'Maternelle',
            2 => 'Primaire',
            3 => 'Secondaire général',
            4 => 'Technique',
        ];
        $section_id = $this->faker->randomElement(array_keys($sections));
        return [
            'nom' => $this->faker->lastName(),
            'prenom' => $this->faker->firstName(),
            'post_nom' => $this->faker->lastName(),
            'matricule' => strtoupper($this->faker->unique()->bothify('MAT###??')),
            'classe' => $this->faker->randomElement(['6ème', '5ème', '4ème', '3ème', '2nde', '1ère', 'Terminale']),
            'section_id' => $section_id,
            'date_naissance' => $this->faker->date('Y-m-d', '-10 years'),
            'sexe' => $this->faker->randomElement(['M', 'F']),
            'adresse' => $this->faker->address(),
            'tuteur' => $this->faker->name(),
            'telephone_tuteur' => $this->faker->phoneNumber(),
        ];
    }
}

