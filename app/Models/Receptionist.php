<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receptionist extends Model
{
    use HasFactory;

    protected $table = 'receptionists'; // Nom de la table dans phpMyAdmin
    protected $fillable = ['name', 'email', 'phone', 'role', 'password'];

    public function getTable()
    {
        return 'receptionists';
    }
}