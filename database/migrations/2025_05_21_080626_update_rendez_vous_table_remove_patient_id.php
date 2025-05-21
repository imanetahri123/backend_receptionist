<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('rendez_vous', function (Blueprint $table) {
            // 1. Supprime la contrainte de clé étrangère si elle existe
            try {
                $table->dropForeign(['patient_id']);
            } catch (\Exception $e) {
                // Si la contrainte n'existe pas, on continue
            }

            // 2. Supprime la colonne patient_id si elle existe
            if (Schema::hasColumn('rendez_vous', 'patient_id')) {
                $table->dropColumn('patient_id');
            }

            // 3. Ajoute les nouvelles colonnes
            if (!Schema::hasColumn('rendez_vous', 'nom_patient')) {
                $table->string('nom_patient')->nullable();
            }

            if (!Schema::hasColumn('rendez_vous', 'prenom_patient')) {
                $table->string('prenom_patient')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('rendez_vous', function (Blueprint $table) {
            // 1. Rajoute patient_id (si tu veux revenir en arrière)
            if (!Schema::hasColumn('rendez_vous', 'patient_id')) {
                $table->unsignedBigInteger('patient_id')->nullable(); // Sans contrainte pour l’instant
            }

            // 2. Supprime les champs nom/prenom
            if (Schema::hasColumn('rendez_vous', 'nom_patient')) {
                $table->dropColumn('nom_patient');
            }

            if (Schema::hasColumn('rendez_vous', 'prenom_patient')) {
                $table->dropColumn('prenom_patient');
            }
        });
    }
};