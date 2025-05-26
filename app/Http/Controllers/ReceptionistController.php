<?php

namespace App\Http\Controllers;

use App\Models\Receptionist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ReceptionistController extends Controller
{
    // Afficher le profil du premier réceptionniste
    public function show(Request $request)
    {
        $user = Receptionist::first();
        if (!$user) {
            return Response::json(['error' => 'Aucun réceptionniste trouvé'], 404);
        }

        // ✅ Gérer correctement les URLs de photo
        if ($user->photo && $user->photo !== '/assets/images/pers.jpg') {
            // Si c'est une photo uploadée, utiliser l'URL complète
            $photo = asset($user->photo);
        } else {
            // Si c'est la photo par défaut, utiliser le chemin Angular
            $photo = '/assets/images/pers.jpg';
        }

        return Response::json([
            'name' => $user->full_name ?? 'Non renseigné',
            'email' => $user->email,
            'phone' => $user->phone ?? 'Non renseigné',
            'role' => $user->role ?? 'Réceptionniste',
            'joinDate' => $user->created_at ? $user->created_at->toDateString() : null,
            'photo' => $photo,
        ]);
    }

    // Upload et mise à jour de la photo de profil
    public function updatePhoto(Request $request)
    {
        try {
            $user = Receptionist::first();
            if (!$user) {
                return Response::json(['error' => 'Aucun réceptionniste trouvé'], 404);
            }

            // ✅ Validation du fichier
            $validator = Validator::make($request->all(), [
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048' // Max 2MB
            ]);

            if ($validator->fails()) {
                return Response::json([
                    'error' => 'Fichier non valide. Formats acceptés : jpeg, png, jpg, gif (max 2MB)',
                    'details' => $validator->errors()
                ], 400);
            }

            if ($request->hasFile('photo')) {
                // ✅ Supprimer l'ancienne photo si elle existe
                if ($user->photo && 
                    $user->photo !== '/assets/images/pers.jpg' && 
                    file_exists(public_path($user->photo))) {
                    unlink(public_path($user->photo));
                }

                $file = $request->file('photo');
                $filename = uniqid('photo_') . '.' . $file->getClientOriginalExtension();
                
                // ✅ Créer le dossier s'il n'existe pas
                $uploadPath = public_path('uploads/profiles');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                $file->move($uploadPath, $filename);
                $user->photo = '/uploads/profiles/' . $filename;
                $user->save();
                
                // ✅ Retourner l'URL complète avec asset()
                return Response::json([
                    'success' => true,
                    'photo' => asset($user->photo),
                    'message' => 'Photo mise à jour avec succès'
                ]);
            }
            
            return Response::json(['error' => 'Aucun fichier reçu'], 400);
            
        } catch (\Exception $e) {
            return Response::json([
                'error' => 'Erreur serveur lors de l\'upload',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Modifier les infos du profil
    public function update(Request $request)
    {
        try {
            $user = Receptionist::first();
            if (!$user) {
                return Response::json(['error' => 'Aucun réceptionniste trouvé'], 404);
            }

            // ✅ Validation des données
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:receptionists,email,' . $user->id,
                'phone' => 'required|string|max:20',
            ]);

            if ($validator->fails()) {
                return Response::json([
                    'error' => $validator->errors()->first()
                ], 400);
            }

            $user->full_name = $request->input('name');
            $user->email = $request->input('email');
            $user->phone = $request->input('phone');
            $user->save();

            return Response::json([
                'success' => true,
                'message' => 'Profil mis à jour avec succès'
            ]);
            
        } catch (\Exception $e) {
            return Response::json([
                'error' => 'Erreur lors de la mise à jour du profil',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Changer le mot de passe
    public function updatePassword(Request $request)
    {
        try {
            $user = Receptionist::first();
            if (!$user) {
                return Response::json(['error' => 'Aucun réceptionniste trouvé'], 404);
            }

            // ✅ Validation du mot de passe
            $validator = Validator::make($request->all(), [
                'newPassword' => 'required|string|min:6|max:255',
            ]);

            if ($validator->fails()) {
                return Response::json([
                    'error' => 'Le mot de passe doit contenir au moins 6 caractères'
                ], 400);
            }

            $newPassword = $request->input('newPassword');
            $user->password = Hash::make($newPassword);
            $user->save();

            return Response::json([
                'success' => true,
                'message' => 'Mot de passe changé avec succès'
            ]);
            
        } catch (\Exception $e) {
            return Response::json([
                'error' => 'Erreur lors du changement de mot de passe',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}