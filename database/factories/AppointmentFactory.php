<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $status = fake()->randomElement(['upcoming', 'completed', 'canceled']);

        return [
            'time' => fake()->time('H:i') . ' - ' . date('H:i', strtotime(fake()->time('H:i') . ' +1 hour')),
            'patient' => fake()->name(),
            'type' => fake()->randomElement(['Consultation', 'Bilan sanguin', 'Suivi', 'Radiographie', 'Urgence']),
            'status' => $status,
            'status_label' => match ($status) {
                'upcoming' => 'À Venir',
                'completed' => 'Terminé',
                'canceled' => 'Annulé',
            },
            'reminder' => fake()->randomElement(['Envoyé', 'En attente', 'Aucun']),
        ];
    }
}