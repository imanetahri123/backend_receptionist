<?php
namespace App\Http\Controllers;
use App\Models\RendezVous;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RendezVousController extends Controller
{
    public function index(Request $request)
    {
        $query = RendezVous::query();
        
        if ($request->has('date') && !empty($request->date)) {
            $query->whereDate('date_heure', $request->date);
        }
        
        if ($request->has('statut') && !empty($request->statut) && in_array($request->statut, ['À Venir', 'En Cours', 'Terminé', 'Annulé'])) {
            $query->where('statut', $request->statut);
        }
        
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom_patient', 'like', "%$search%")
                  ->orWhere('prenom_patient', 'like', "%$search%");
            });
        }
        
        return response()->json($query->orderBy('date_heure', 'desc')->paginate(10));
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

        $validated['type'] = $validated['type'] ?? 'Consultation';
        $validated['statut'] = $validated['statut'] ?? 'À Venir';
        
        $rendezVous = RendezVous::create($validated);
        
        return response()->json([
            'message' => 'Rendez-vous ajouté avec succès',
            'rendez_vous' => $rendezVous
        ], 201);
    }

    public function show($id)
    {
        $rendezVous = RendezVous::findOrFail($id);
        return response()->json($rendezVous);
    }

    public function update(Request $request, $id)
    {
        // Trouver le rendez-vous par ID
        $rendezVous = RendezVous::findOrFail($id);
        
        Log::info('=== DÉBUT MISE À JOUR ===');
        Log::info('ID du RDV à modifier: ' . $id);
        Log::info('RDV trouvé: ', $rendezVous->toArray());
        Log::info('Données reçues: ', $request->all());
        
        $validated = $request->validate([
            'nom_patient' => 'required|string|max:255',
            'prenom_patient' => 'nullable|string|max:255',
            'date_heure' => 'required|date_format:Y-m-d H:i:s',
            'type' => 'nullable|in:Consultation,Suivi,Autre',
            'statut' => 'required|in:À Venir,En Cours,Terminé,Annulé',
            'rappel' => 'nullable|string',
        ]);

        // Assigner les valeurs par défaut si nécessaire
        $validated['type'] = $validated['type'] ?? 'Consultation';
        $validated['prenom_patient'] = $validated['prenom_patient'] ?? '';
        $validated['rappel'] = $validated['rappel'] ?? null;

        Log::info('Données validées: ', $validated);

        // Mise à jour directe avec WHERE pour être sûr
        $updated = RendezVous::where('id', $id)->update($validated);
        
        Log::info('Nombre de lignes mises à jour: ' . $updated);
        
        if ($updated > 0) {
            // Récupérer le rendez-vous mis à jour
            $rendezVous = RendezVous::find($id);
            Log::info('RDV après mise à jour: ', $rendezVous->toArray());
            
            return response()->json([
                'message' => 'Rendez-vous mis à jour avec succès',
                'rendez_vous' => $rendezVous
            ]);
        } else {
            Log::error('Aucune ligne mise à jour pour ID: ' . $id);
            return response()->json([
                'message' => 'Erreur: Aucune modification effectuée'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $rendezVous = RendezVous::findOrFail($id);
            Log::info('Suppression RDV ID: ' . $id);
            
            $deleted = $rendezVous->delete();
            
            if ($deleted) {
                return response()->json([
                    'message' => 'Rendez-vous supprimé avec succès'
                ]);
            } else {
                return response()->json([
                    'message' => 'Erreur lors de la suppression'
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Erreur suppression: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erreur lors de la suppression',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}