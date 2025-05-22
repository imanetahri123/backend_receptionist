<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\RendezVousController;

Route::get('/profile/{email}', function ($email): JsonResponse {
    return response()->json([
        'name' => 'Omar Bennani',
        'email' => $email,
        'phone' => '+212708335842',
        'role' => 'Réceptionniste',
        'photo' => '/assets/images/pers.jpg',
        'joinDate' => '2023-01-01',
        'stats' => [
            'appointments' => 245,
            'doctors' => 12,
            'bills' => 180
        ]
    ]);
});

Route::apiResource('appointments', AppointmentController::class);
Route::apiResource('patients', PatientController::class);
Route::apiResource('rendez_vous', RendezVousController::class);