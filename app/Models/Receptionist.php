<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class Receptionist extends Authenticatable
{
    use HasFactory;

    protected $table = 'receptionists';
    protected $fillable = ['full_name', 'email', 'phone', 'role', 'password', 'photo']; // ✅ full_name

    // ✅ Cache automatiquement les mots de passe
    protected $hidden = ['password'];

    // ✅ Hash automatique du mot de passe
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    // ✅ Accesseur pour l'URL complète de la photo
    public function getPhotoUrlAttribute()
    {
        return $this->photo ? asset($this->photo) : 'https://via.placeholder.com/150';
    }
}