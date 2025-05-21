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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->date('dob')->nullable(); // Date de naissance
            $table->string('nationality')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('marital_status')->nullable(); // État civil
            $table->string('gender')->nullable(); // Sexe
            $table->string('address')->nullable();
            $table->string('photo')->nullable();
            $table->string('status')->default('Actif'); // Actif, En Suivi, Urgent, etc.
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
        Schema::dropIfExists('patients');
    }
};