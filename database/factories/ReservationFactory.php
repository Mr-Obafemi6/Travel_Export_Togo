<?php

namespace Database\Factories;

use App\Models\Excursion;
use App\Models\Reservation;
use App\Models\Utilisateur;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nb_personnes' => fake()->numberBetween(1, 3),
            'montant' => fake()->numberBetween(10000, 100000),
            'statut' => Reservation::STATUT_EN_ATTENTE,
            'id_utilisateur' => Utilisateur::factory(),
            'id_excursion' => Excursion::factory(),
        ];
    }
}
