<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'appointment_date' => fake()->dateTimeBetween('now', '+3 months'),
            'status' => fake()->randomElement(['pending', 'confirmed', 'canceled']),
            'notes' => fake()->optional(0.7)->sentence(), 
        ];
    }
}