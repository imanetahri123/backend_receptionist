<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\RendezVousController;

Route::get('/profile/*{email}*', function ($email): JsonResponse {
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

// Routes explicites pour rendez_vous pour éviter les conflits
Route::get('rendez_vous', [RendezVousController::class, 'index']);
Route::post('rendez_vous', [RendezVousController::class, 'store']);
Route::get('rendez_vous/{id}', [RendezVousController::class, 'show']);
Route::put('rendez_vous/{id}', [RendezVousController::class, 'update']);
Route::delete('rendez_vous/{id}', [RendezVousController::class, 'destroy']);