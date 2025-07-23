<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'eleve_id',
        'fee_type_id',
        'montant',
        'date_paiement',
        'statut',
        'agent_encaisseur',
        'numero_recu',
        'remarque',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'eleve_id');
    }

    public function feeType()
    {
        return $this->belongsTo(FeeType::class);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_encaisseur');
    }
}

