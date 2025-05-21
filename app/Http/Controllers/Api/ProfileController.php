<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    /**
     * Récupère les informations du profil de l'utilisateur connecté.
     */
    public function show(Request $request)
    {
        // ✅ Renvoie l'utilisateur authentifié via Sanctum
        return response()->json($request->user());
    }
}