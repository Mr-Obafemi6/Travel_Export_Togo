<?php

namespace Tests\Feature;

use App\Models\Categorie;
use App\Models\Destination;
use App\Models\Excursion;
use App\Models\Photo;
use App\Models\Region;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_les_pages_publiques_sont_accessibles(): void
    {
        $region = Region::factory()->create(['nom_region' => 'Maritime']);
        $categorie = Categorie::factory()->create(['libelle' => 'Plage']);
        $destination = Destination::factory()->create(['id_region' => $region->id_region]);
        $destination->categories()->attach($categorie);
        Photo::create([
            'chemin' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e',
            'legende' => 'Plage de Lomé',
            'id_destination' => $destination->id_destination,
        ]);
        $excursion = Excursion::factory()->create([
            'id_destination' => $destination->id_destination,
            'date_excursion' => now()->addDays(10),
        ]);

        $this->get(route('home'))->assertOk();
        $this->get(route('destinations.index'))->assertOk()->assertSee('Plage');
        $this->get(route('destinations.show', $destination))->assertOk()->assertSee($destination->nom_destination);
        $this->get(route('excursions.index'))->assertOk()->assertSee($excursion->titre);
        $this->get(route('excursions.show', $excursion))->assertOk()->assertSee($excursion->titre);
        $this->get(route('contact.create'))->assertOk();
        $this->get(route('login'))->assertOk();
        $this->get(route('register'))->assertOk();
    }

    public function test_une_page_inexistante_renvoie_une_404(): void
    {
        $this->get('/cette-page-nexiste-pas')->assertNotFound();
    }

    public function test_le_filtrage_des_destinations_par_region_fonctionne(): void
    {
        $maritime = Region::factory()->create(['nom_region' => 'Maritime']);
        $plateaux = Region::factory()->create(['nom_region' => 'Plateaux']);

        $lome = Destination::factory()->create([
            'nom_destination' => 'Lomé',
            'id_region' => $maritime->id_region,
        ]);
        $kpalime = Destination::factory()->create([
            'nom_destination' => 'Kpalimé',
            'id_region' => $plateaux->id_region,
        ]);

        $this->get(route('destinations.index', ['region' => $maritime->id_region]))
            ->assertOk()
            ->assertSee($lome->nom_destination)
            ->assertDontSee($kpalime->description);
    }
}
