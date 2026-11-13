<?php

namespace Database\Factories;

use App\Models\Destination;
use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Destination>
 */
class DestinationFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom_destination' => fake()->unique()->city(),
            'description' => fake()->paragraph(),
            'latitude' => fake()->latitude(6, 11),
            'longitude' => fake()->longitude(0, 2),
            'id_region' => Region::factory(),
        ];
    }
}
