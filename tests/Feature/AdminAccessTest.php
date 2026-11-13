<?php

namespace Tests\Feature;

use App\Models\Excursion;
use App\Models\Reservation;
use App\Models\Utilisateur;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_visiteur_est_redirige_vers_la_connexion(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }

    public function test_un_touriste_ne_peut_pas_acceder_a_l_administration(): void
    {
        $touriste = Utilisateur::factory()->create();

        $this->actingAs($touriste)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_un_administrateur_peut_acceder_au_tableau_de_bord(): void
    {
        $admin = Utilisateur::factory()->administrateur()->create();

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Vue d’ensemble');
    }

    public function test_les_pages_de_liste_de_l_administration_s_affichent(): void
    {
        $admin = Utilisateur::factory()->administrateur()->create();
        $excursion = Excursion::factory()->create(['date_excursion' => now()->addDays(10)]);
        Reservation::factory()->create(['id_excursion' => $excursion->id_excursion]);

        $routes = [
            'admin.regions.index',
            'admin.categories.index',
            'admin.destinations.index',
            'admin.excursions.index',
            'admin.reservations.index',
            'admin.utilisateurs.index',
            'admin.messages.index',
        ];

        foreach ($routes as $route) {
            $this->actingAs($admin)->get(route($route))->assertOk();
        }
    }

    public function test_un_administrateur_peut_confirmer_une_reservation(): void
    {
        $admin = Utilisateur::factory()->administrateur()->create();
        $excursion = Excursion::factory()->create(['date_excursion' => now()->addDays(10)]);
        $reservation = Reservation::factory()->create([
            'id_excursion' => $excursion->id_excursion,
            'statut' => Reservation::STATUT_EN_ATTENTE,
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.reservations.statut', $reservation), [
            'statut' => Reservation::STATUT_CONFIRMEE,
        ]);

        $response->assertRedirect(route('admin.reservations.show', $reservation));
        $this->assertSame(Reservation::STATUT_CONFIRMEE, $reservation->fresh()->statut);
    }

    public function test_une_transition_de_statut_non_autorisee_est_refusee(): void
    {
        $admin = Utilisateur::factory()->administrateur()->create();
        $excursion = Excursion::factory()->create(['date_excursion' => now()->addDays(10)]);
        $reservation = Reservation::factory()->create([
            'id_excursion' => $excursion->id_excursion,
            'statut' => Reservation::STATUT_TERMINEE,
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.reservations.statut', $reservation), [
            'statut' => Reservation::STATUT_CONFIRMEE,
        ]);

        $response->assertSessionHasErrors('statut');
        $this->assertSame(Reservation::STATUT_TERMINEE, $reservation->fresh()->statut);
    }

    public function test_un_administrateur_ne_peut_pas_bloquer_son_propre_compte(): void
    {
        $admin = Utilisateur::factory()->administrateur()->create();

        $response = $this->actingAs($admin)
            ->patch(route('admin.utilisateurs.blocage', $admin));

        $response->assertSessionHasErrors('blocage');
        $this->assertFalse($admin->fresh()->est_bloque);
    }

    public function test_un_administrateur_peut_bloquer_un_touriste(): void
    {
        $admin = Utilisateur::factory()->administrateur()->create();
        $touriste = Utilisateur::factory()->create();

        $response = $this->actingAs($admin)
            ->patch(route('admin.utilisateurs.blocage', $touriste));

        $response->assertSessionHasNoErrors();
        $this->assertTrue($touriste->fresh()->est_bloque);
    }

    public function test_un_administrateur_ne_peut_pas_bloquer_un_autre_administrateur(): void
    {
        $admin = Utilisateur::factory()->administrateur()->create();
        $autreAdmin = Utilisateur::factory()->administrateur()->create();

        $response = $this->actingAs($admin)
            ->patch(route('admin.utilisateurs.blocage', $autreAdmin));

        $response->assertSessionHasErrors('blocage');
        $this->assertFalse($autreAdmin->fresh()->est_bloque);
    }
}
