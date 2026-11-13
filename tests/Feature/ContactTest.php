<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\Utilisateur;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_visiteur_peut_envoyer_un_message(): void
    {
        $response = $this->from(route('contact.create'))->post(route('contact.store'), [
            'nom' => 'Marc Lemaire',
            'email' => 'marc@example.com',
            'sujet' => 'Demande d’information',
            'message' => 'Bonjour, je souhaite organiser un séjour de deux semaines au Togo.',
        ]);

        $response->assertRedirect(route('contact.create'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('contact_messages', [
            'nom' => 'Marc Lemaire',
            'email' => 'marc@example.com',
            'lu' => false,
            'id_utilisateur' => null,
        ]);
    }

    public function test_un_message_invalide_est_refuse(): void
    {
        $response = $this->from(route('contact.create'))->post(route('contact.store'), [
            'nom' => '',
            'email' => 'pas-un-email',
            'sujet' => '',
            'message' => 'Court',
        ]);

        $response->assertSessionHasErrors(['nom', 'email', 'sujet', 'message']);
        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_un_utilisateur_connecte_est_rattache_a_son_message(): void
    {
        $utilisateur = Utilisateur::factory()->create();

        $this->actingAs($utilisateur)->post(route('contact.store'), [
            'nom' => $utilisateur->nom,
            'email' => $utilisateur->email,
            'sujet' => 'Question sur une réservation',
            'message' => 'Bonjour, je souhaite modifier le nombre de personnes de ma réservation.',
        ]);

        $message = ContactMessage::firstOrFail();

        $this->assertSame($utilisateur->id_utilisateur, $message->id_utilisateur);
    }
}
