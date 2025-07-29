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
        'section_id',
        'date_naissance',
        'sexe',
        'adresse',
        'tuteur',
        'telephone_tuteur',
        'total_a_payer',
        'mois_repartition',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class, 'eleve_id');
    }

    public function section()
    {
        return $this->belongsTo(FeeType::class, 'section_id');
    }
}
