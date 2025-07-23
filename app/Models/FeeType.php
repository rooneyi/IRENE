<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeType extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'montant_par_defaut',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}

