<?php

namespace Database\Factories;

use App\Models\Utilisateur;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Utilisateur>
 */
class UtilisateurFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'mot_de_passe' => 'Touriste@2026',
            'role' => Utilisateur::ROLE_TOURISTE,
            'est_bloque' => false,
        ];
    }

    public function administrateur(): static
    {
        return $this->state(fn (): array => [
            'role' => Utilisateur::ROLE_ADMINISTRATEUR,
        ]);
    }

    public function bloque(): static
    {
        return $this->state(fn (): array => [
            'est_bloque' => true,
        ]);
    }
}
