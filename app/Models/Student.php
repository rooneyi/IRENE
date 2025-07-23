<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'matricule',
        'classe',
        'date_naissance',
        'sexe',
        'adresse',
        'tuteur',
        'telephone_tuteur',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class, 'eleve_id');
    }
}

