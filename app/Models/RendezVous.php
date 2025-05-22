<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RendezVous extends Model
{
    use HasFactory;
    
    protected $table = 'rendez_vous';
    
    protected $fillable = [
        'nom_patient',
        'prenom_patient',
        'date_heure',
        'type',
        'statut',
        'rappel'
    ];
    
    protected $casts = [
        'date_heure' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    // Désactiver les timestamps automatiques si votre table n'en a pas
    // public $timestamps = false;
}