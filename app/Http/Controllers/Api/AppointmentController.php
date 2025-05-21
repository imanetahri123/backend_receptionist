<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $appointments = Appointment::all();

        return response()->json([
            'success' => true,
            'data' => $appointments
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        // Validation des données
        $validated = $request->validate([
            'time' => 'required|string',
            'patient' => 'required|string',
            'type' => 'nullable|string',
            'status' => 'required|in:upcoming,completed,canceled',
            'reminder' => 'nullable|string'
        ]);

        // Définir le label du statut
        $validated['status_label'] = match ($validated['status']) {
            'upcoming' => 'À Venir',
            'completed' => 'Terminé',
            'canceled' => 'Annulé',
        };

        // Créer le RDV
        $appointment = Appointment::create($validated);

        return response()->json([
            'success' => true,
            'data' => $appointment,
            'message' => 'Rendez-vous créé avec succès.'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): JsonResponse
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return response()->json([
                'success' => false,
                'message' => 'Rendez-vous non trouvé.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $appointment
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return response()->json([
                'success' => false,
                'message' => 'Rendez-vous non trouvé.'
            ], 404);
        }

        // Validation des champs
        $validated = $request->validate([
            'time' => 'sometimes|required|string',
            'patient' => 'sometimes|required|string',
            'type' => 'sometimes|nullable|string',
            'status' => 'sometimes|required|in:upcoming,completed,canceled',
            'reminder' => 'sometimes|nullable|string'
        ]);

        // Mise à jour du statut label si status change
        if (isset($validated['status'])) {
            $validated['status_label'] = match ($validated['status']) {
                'upcoming' => 'À Venir',
                'completed' => 'Terminé',
                'canceled' => 'Annulé',
            };
        }

        $appointment->update($validated);

        return response()->json([
            'success' => true,
            'data' => $appointment,
            'message' => 'Rendez-vous mis à jour.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $deleted = Appointment::destroy($id);

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Rendez-vous non trouvé.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Rendez-vous supprimé avec succès.'
        ]);
    }
}