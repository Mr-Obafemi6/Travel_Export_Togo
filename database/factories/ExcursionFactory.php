<?php

namespace Database\Factories;

use App\Models\Destination;
use App\Models\Excursion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Excursion>
 */
class ExcursionFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titre' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'prix' => fake()->numberBetween(5000, 50000),
            'duree_heures' => fake()->numberBetween(2, 10),
            'places_max' => fake()->numberBetween(5, 30),
            'date_excursion' => now()->addDays(fake()->numberBetween(3, 60)),
            'id_destination' => Destination::factory(),
        ];
    }
}
