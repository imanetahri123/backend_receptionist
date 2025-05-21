<?php

namespace App\Http\Controllers;

use App\Models\Receptionist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ReceptionistController extends Controller
{
    // Afficher le profil du réceptionniste
    public function show($email)
    {
        // Récupérer l'utilisateur par email
        $user = Receptionist::where('email', $email)->first();

        // Si l'utilisateur n'existe pas, renvoyer une erreur 404
        if (!$user) {
            return Response::json(['error' => 'Utilisateur non trouvé'], 404);
        }

        // Retourner les données en JSON
        return Response::json([
            'name' => $user->name ?? 'Non renseigné',
            'email' => $user->email,
            'phone' => $user->phone ?? 'Non renseigné',
            'role' => $user->role ?? 'Réceptionniste',
            'joinDate' => $user->created_at ? $user->created_at->toDateString() : null,
            'photo' => asset('assets/images/pers.jpg'), // Chemin de l'image sur le backend
            'stats' => [
                'appointments' => 245,
                'doctors' => 12,
                'bills' => 180
            ]
        ]);
    }
}