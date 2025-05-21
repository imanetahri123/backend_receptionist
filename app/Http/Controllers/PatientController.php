<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    // Afficher la liste des patients
    public function index()
    {
        return Patient::all();
    }

    // Créer un nouveau patient
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:patients',
            'phone' => 'nullable|string',
            'dob' => 'nullable|date',
            'nationality' => 'nullable|string',
            'blood_group' => 'nullable|string',
            'marital_status' => 'nullable|string',
            'gender' => 'nullable|string',
            'address' => 'nullable|string',
            'status' => 'nullable|string',
            'photo' => 'nullable|file|image|max:2048', // max 2Mo
        ]);

        // Gestion de l'upload de la photo
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('patients', 'public');
            $validated['photo'] = '/storage/' . $path;
        } else {
            $validated['photo'] = '/assets/images/default-user.png';
        }

        return Patient::create($validated);
    }

    // Afficher un patient spécifique
    public function show($id)
    {
        return Patient::findOrFail($id);
    }

    // Mettre à jour un patient
    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string',
            'email' => 'sometimes|required|email|unique:patients,email,' . $id,
            'phone' => 'nullable|string',
            'dob' => 'nullable|date',
            'nationality' => 'nullable|string',
            'blood_group' => 'nullable|string',
            'marital_status' => 'nullable|string',
            'gender' => 'nullable|string',
            'address' => 'nullable|string',
            'status' => 'nullable|string',
            'photo' => 'nullable|file|image|max:2048',
        ]);

        // Gestion de l'upload de la photo lors de la mise à jour
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('patients', 'public');
            $validated['photo'] = '/storage/' . $path;
        }

        $patient->update($validated);
        return $patient;
    }

    // Supprimer un patient
    public function destroy($id)
    {
        Patient::destroy($id);
        return response()->noContent();
    }
}