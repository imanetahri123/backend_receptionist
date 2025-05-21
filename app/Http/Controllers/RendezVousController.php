<?php

namespace App\Http\Controllers;

use App\Models\RendezVous;
use Illuminate\Http\Request;

class RendezVousController extends Controller
{
    public function index(Request $request)
    {
        $query = RendezVous::query();

        if ($request->has('date')) {
            $query->whereDate('date_heure', $request->date);
        }

        if ($request->has('statut') && in_array($request->statut, ['À Venir', 'En Cours', 'Terminé', 'Annulé'])) {
            $query->where('statut', $request->statut);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nom_patient', 'like', "%$search%")
                  ->orWhere('prenom_patient', 'like', "%$search%");
        }

        return $query->paginate(10);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_patient' => 'required|string|max:255',
            'prenom_patient' => 'nullable|string|max:255',
            'date_heure' => 'required|date_format:Y-m-d H:i:s',
            'type' => 'nullable|in:Consultation,Suivi,Autre',
            'statut' => 'nullable|in:À Venir,En Cours,Terminé,Annulé',
            'rappel' => 'nullable|string',
        ]);

        $rendezVous = RendezVous::create($validated);

        return response()->json([
            'message' => 'Rendez-vous ajouté avec succès',
            'rendez_vous' => $rendezVous
        ], 201);
    }

    public function show(RendezVous $rendezVous)
    {
        return $rendezVous;
    }

    public function update(Request $request, RendezVous $rendezVous)
    {
        $validated = $request->validate([
            'nom_patient' => 'sometimes|string|max:255',
            'prenom_patient' => 'sometimes|string|max:255',
            'date_heure' => 'sometimes|date_format:Y-m-d H:i:s',
            'type' => 'sometimes|in:Consultation,Suivi,Autre',
            'statut' => 'sometimes|in:À Venir,En Cours,Terminé,Annulé',
            'rappel' => 'sometimes|string',
        ]);

        $rendezVous->update($validated);

        return response()->json([
            'message' => 'Rendez-vous mis à jour',
            'rendez_vous' => $rendezVous
        ]);
    }

    public function destroy(RendezVous $rendezVous)
    {
        $rendezVous->delete();
        return response()->json(['message' => 'Rendez-vous supprimé']);
    }
}