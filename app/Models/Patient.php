<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'dob',
        'nationality',
        'blood_group',
        'marital_status',
        'gender',
        'address',
        'photo',
        'status',
    ];
}