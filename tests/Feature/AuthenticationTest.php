<?php

namespace Tests\Feature;

use App\Models\Utilisateur;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_visiteur_peut_creer_un_compte(): void
    {
        $response = $this->post(route('register.store'), [
            'nom' => 'Ama Kossi',
            'email' => 'ama.kossi@example.com',
            'password' => 'Motdepasse123',
            'password_confirmation' => 'Motdepasse123',
        ]);

        $response->assertRedirect(route('reservations.index'));
        $this->assertAuthenticated();

        $this->assertDatabaseHas('utilisateurs', [
            'nom' => 'Ama Kossi',
            'email' => 'ama.kossi@example.com',
            'role' => Utilisateur::ROLE_TOURISTE,
        ]);
    }

    public function test_un_utilisateur_peut_se_connecter(): void
    {
        $utilisateur = Utilisateur::factory()->create([
            'email' => 'koffi@example.com',
            'mot_de_passe' => 'Touriste@2026',
        ]);

        $response = $this->post(route('login.store'), [
            'email' => 'koffi@example.com',
            'password' => 'Touriste@2026',
        ]);

        $response->assertRedirect(route('reservations.index'));
        $this->assertAuthenticatedAs($utilisateur);
    }

    public function test_un_administrateur_est_redirige_vers_le_tableau_de_bord(): void
    {
        Utilisateur::factory()->administrateur()->create([
            'email' => 'admin@example.com',
            'mot_de_passe' => 'Admin@2026',
        ]);

        $response = $this->post(route('login.store'), [
            'email' => 'admin@example.com',
            'password' => 'Admin@2026',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_un_compte_bloque_ne_peut_pas_se_connecter(): void
    {
        Utilisateur::factory()->bloque()->create([
            'email' => 'bloque@example.com',
            'mot_de_passe' => 'Touriste@2026',
        ]);

        $response = $this->from(route('login'))->post(route('login.store'), [
            'email' => 'bloque@example.com',
            'password' => 'Touriste@2026',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_un_mot_de_passe_incorrect_est_refuse(): void
    {
        Utilisateur::factory()->create([
            'email' => 'koffi@example.com',
            'mot_de_passe' => 'Touriste@2026',
        ]);

        $response = $this->from(route('login'))->post(route('login.store'), [
            'email' => 'koffi@example.com',
            'password' => 'mauvais-mot-de-passe',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_un_utilisateur_bloque_est_deconnecte_de_son_espace(): void
    {
        $utilisateur = Utilisateur::factory()->bloque()->create();

        $this->actingAs($utilisateur)
            ->get(route('reservations.index'))
            ->assertRedirect(route('login'));
    }

    public function test_un_utilisateur_peut_se_deconnecter(): void
    {
        $utilisateur = Utilisateur::factory()->create();

        $response = $this->actingAs($utilisateur)->post(route('logout'));

        $response->assertRedirect(route('home'));
        $this->assertGuest();
    }
}
