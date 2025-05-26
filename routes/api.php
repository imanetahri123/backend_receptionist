<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReceptionistController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\RendezVousController;

// Récupère le profil du réceptionniste connecté via Sanctum

Route::get('/profile', [ReceptionistController::class, 'show']);
Route::put('/profile', [ReceptionistController::class, 'update']);
Route::put('/profile/password', [ReceptionistController::class, 'updatePassword']);
Route::post('/profile/photo', [ReceptionistController::class, 'updatePhoto']);


// Autres routes API
Route::apiResource('appointments', AppointmentController::class);
Route::apiResource('patients', PatientController::class);

// Routes pour rendez_vous
Route::get('rendez_vous', [RendezVousController::class, 'index']);
Route::post('rendez_vous', [RendezVousController::class, 'store']);
Route::get('rendez_vous/{id}', [RendezVousController::class, 'show']);
Route::put('rendez_vous/{id}', [RendezVousController::class, 'update']);
Route::delete('rendez_vous/{id}', [RendezVousController::class, 'destroy']);