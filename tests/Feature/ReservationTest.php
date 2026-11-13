<?php

namespace Tests\Feature;

use App\Models\Excursion;
use App\Models\Reservation;
use App\Models\Utilisateur;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    private function creerExcursion(array $attributs = []): Excursion
    {
        return Excursion::factory()->create(array_merge([
            'prix' => 15000,
            'places_max' => 10,
            'date_excursion' => now()->addDays(10),
        ], $attributs));
    }

    public function test_un_visiteur_non_connecte_ne_peut_pas_reserver(): void
    {
        $excursion = $this->creerExcursion();

        $this->post(route('reservations.store', $excursion), ['nb_personnes' => 1])
            ->assertRedirect(route('login'));
    }

    public function test_le_montant_est_calcule_cote_serveur(): void
    {
        $utilisateur = Utilisateur::factory()->create();
        $excursion = $this->creerExcursion();

        $response = $this->actingAs($utilisateur)->post(route('reservations.store', $excursion), [
            'nb_personnes' => 3,
            'montant' => 1,
            'statut' => Reservation::STATUT_CONFIRMEE,
        ]);

        $response->assertRedirect(route('reservations.index'));

        $reservation = Reservation::firstOrFail();

        $this->assertSame(3, $reservation->nb_personnes);
        $this->assertSame(45000.0, (float) $reservation->montant);
        $this->assertSame(Reservation::STATUT_EN_ATTENTE, $reservation->statut);
        $this->assertSame($utilisateur->id_utilisateur, $reservation->id_utilisateur);
    }

    public function test_la_capacite_de_l_excursion_est_respectee(): void
    {
        $utilisateur = Utilisateur::factory()->create();
        $excursion = $this->creerExcursion(['places_max' => 5]);

        Reservation::factory()->create([
            'id_excursion' => $excursion->id_excursion,
            'nb_personnes' => 4,
            'statut' => Reservation::STATUT_CONFIRMEE,
        ]);

        $response = $this->actingAs($utilisateur)->post(route('reservations.store', $excursion), [
            'nb_personnes' => 2,
        ]);

        $response->assertSessionHasErrors('nb_personnes');
        $this->assertDatabaseCount('reservations', 1);
    }

    public function test_une_excursion_complete_ne_peut_pas_etre_reservee(): void
    {
        $utilisateur = Utilisateur::factory()->create();
        $excursion = $this->creerExcursion(['places_max' => 2]);

        Reservation::factory()->create([
            'id_excursion' => $excursion->id_excursion,
            'nb_personnes' => 2,
            'statut' => Reservation::STATUT_EN_ATTENTE,
        ]);

        $response = $this->actingAs($utilisateur)->post(route('reservations.store', $excursion), [
            'nb_personnes' => 1,
        ]);

        $response->assertSessionHasErrors('nb_personnes');
        $this->assertDatabaseCount('reservations', 1);
    }

    public function test_une_excursion_passee_ne_peut_pas_etre_reservee(): void
    {
        $utilisateur = Utilisateur::factory()->create();
        $excursion = $this->creerExcursion(['date_excursion' => now()->subDay()]);

        $response = $this->actingAs($utilisateur)->post(route('reservations.store', $excursion), [
            'nb_personnes' => 1,
        ]);

        $response->assertSessionHasErrors('nb_personnes');
    }

    public function test_un_touriste_peut_annuler_plus_de_48_heures_avant(): void
    {
        $utilisateur = Utilisateur::factory()->create();
        $excursion = $this->creerExcursion(['date_excursion' => now()->addDays(5)]);
        $reservation = Reservation::factory()->create([
            'id_utilisateur' => $utilisateur->id_utilisateur,
            'id_excursion' => $excursion->id_excursion,
            'statut' => Reservation::STATUT_CONFIRMEE,
        ]);

        $response = $this->actingAs($utilisateur)
            ->patch(route('reservations.cancel', $reservation));

        $response->assertSessionHasNoErrors();
        $this->assertSame(Reservation::STATUT_ANNULEE, $reservation->fresh()->statut);
    }

    public function test_l_annulation_est_refusee_moins_de_48_heures_avant(): void
    {
        $utilisateur = Utilisateur::factory()->create();
        $excursion = $this->creerExcursion(['date_excursion' => now()->addDay()]);
        $reservation = Reservation::factory()->create([
            'id_utilisateur' => $utilisateur->id_utilisateur,
            'id_excursion' => $excursion->id_excursion,
            'statut' => Reservation::STATUT_CONFIRMEE,
        ]);

        $response = $this->actingAs($utilisateur)
            ->patch(route('reservations.cancel', $reservation));

        $response->assertSessionHasErrors('annulation');
        $this->assertSame(Reservation::STATUT_CONFIRMEE, $reservation->fresh()->statut);
    }

    public function test_un_touriste_ne_peut_pas_annuler_la_reservation_d_un_autre(): void
    {
        $titulaire = Utilisateur::factory()->create();
        $intrus = Utilisateur::factory()->create();
        $excursion = $this->creerExcursion(['date_excursion' => now()->addDays(5)]);
        $reservation = Reservation::factory()->create([
            'id_utilisateur' => $titulaire->id_utilisateur,
            'id_excursion' => $excursion->id_excursion,
            'statut' => Reservation::STATUT_CONFIRMEE,
        ]);

        $this->actingAs($intrus)
            ->patch(route('reservations.cancel', $reservation))
            ->assertForbidden();

        $this->assertSame(Reservation::STATUT_CONFIRMEE, $reservation->fresh()->statut);
    }
}
