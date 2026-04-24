<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    public function definition(): array
    {
        $medicalServices = [
            'Consultation Générale', 'Suivi de Traitement', 'Examen de Routine', 
            'Consultation Pédiatrique', 'Bilan Sanguin', 'Vaccination'
        ];

        return [
            'name' => fake()->unique()->randomElement($medicalServices),
            'description' => fake()->sentence(10),
            'duration_minutes' => fake()->randomElement([15, 30, 45, 60]),
            'price' => fake()->randomFloat(2, 100, 500), 
        ];
    }
}
