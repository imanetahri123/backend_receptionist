<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rendez_vous', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade'); // Lien vers le patient
            $table->dateTime('date_heure'); // Date et heure du rendez-vous
            $table->enum('type', ['Consultation', 'Suivi', 'Autre'])->default('Consultation');
            $table->enum('statut', ['À Venir', 'En Cours', 'Terminé', 'Annulé'])->default('À Venir');
            $table->text('rappel')->nullable(); // Champ rappel
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rendez_vous');
    }
};