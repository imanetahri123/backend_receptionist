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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('time');               // Heure du RDV
            $table->string('patient');            // Nom du patient
            $table->string('type')->default('Consultation'); // Type de RDV
            $table->enum('status', ['upcoming', 'completed', 'canceled'])->default('upcoming');
            $table->string('status_label');        // Version affichée : "À Venir", ...
            $table->string('reminder')->nullable(); // Rappel : null, "Envoyé", "En attente"
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
        Schema::dropIfExists('appointments');
    }
};