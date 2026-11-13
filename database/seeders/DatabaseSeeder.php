<?php

namespace Database\Seeders;

use App\Models\Categorie;
use App\Models\ContactMessage;
use App\Models\Destination;
use App\Models\Excursion;
use App\Models\Photo;
use App\Models\Region;
use App\Models\Reservation;
use App\Models\Utilisateur;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Remplit la base avec un catalogue de démonstration togolais.
     */
    public function run(): void
    {
        $regions = $this->creerRegions();
        $categories = $this->creerCategories();
        $utilisateurs = $this->creerUtilisateurs();
        $destinations = $this->creerDestinations($regions, $categories);
        $excursions = $this->creerExcursions($destinations);

        $this->creerReservations($utilisateurs, $excursions);
        $this->creerMessages($utilisateurs);
    }

    /**
     * @return array<string, Region>
     */
    private function creerRegions(): array
    {
        $noms = ['Maritime', 'Plateaux', 'Centrale', 'Kara', 'Savanes'];
        $regions = [];

        foreach ($noms as $nom) {
            $regions[$nom] = Region::create(['nom_region' => $nom]);
        }

        return $regions;
    }

    /**
     * @return array<string, Categorie>
     */
    private function creerCategories(): array
    {
        $libelles = ['Plage', 'Montagne', 'Cascade', 'Patrimoine', 'Culture', 'Nature', 'Ville', 'Marché'];
        $categories = [];

        foreach ($libelles as $libelle) {
            $categories[$libelle] = Categorie::create(['libelle' => $libelle]);
        }

        return $categories;
    }

    /**
     * @return array<string, Utilisateur>
     */
    private function creerUtilisateurs(): array
    {
        $utilisateurs = [
            'admin' => Utilisateur::create([
                'nom' => 'Akouvi Mensah',
                'email' => 'admin@traveltogo.tg',
                'mot_de_passe' => 'Admin@2026',
                'role' => Utilisateur::ROLE_ADMINISTRATEUR,
            ]),
            'touriste' => Utilisateur::create([
                'nom' => 'Koffi Adjo',
                'email' => 'touriste@traveltogo.tg',
                'mot_de_passe' => 'Touriste@2026',
                'role' => Utilisateur::ROLE_TOURISTE,
            ]),
            'awa' => Utilisateur::create([
                'nom' => 'Awa Diallo',
                'email' => 'awa.diallo@example.com',
                'mot_de_passe' => 'Touriste@2026',
                'role' => Utilisateur::ROLE_TOURISTE,
            ]),
            'yao' => Utilisateur::create([
                'nom' => 'Yao Kouassi',
                'email' => 'yao.kouassi@example.com',
                'mot_de_passe' => 'Touriste@2026',
                'role' => Utilisateur::ROLE_TOURISTE,
            ]),
            'fatou' => Utilisateur::create([
                'nom' => 'Fatou Traoré',
                'email' => 'fatou.traore@example.com',
                'mot_de_passe' => 'Touriste@2026',
                'role' => Utilisateur::ROLE_TOURISTE,
            ]),
        ];

        $utilisateurs['yao']->update(['est_bloque' => true]);

        return $utilisateurs;
    }

    /**
     * @param  array<string, Region>  $regions
     * @param  array<string, Categorie>  $categories
     * @return array<string, Destination>
     */
    private function creerDestinations(array $regions, array $categories): array
    {
        $donnees = [
            [
                'nom' => 'Lomé',
                'region' => 'Maritime',
                'description' => 'Capitale du Togo, Lomé marie l’effervescence d’une grande ville ouest-africaine à la douceur de ses plages bordées de cocotiers. Flânez le long du boulevard du 13 Janvier, découvrez le marché aux fétiches de Bè et laissez-vous porter par l’énergie des quartiers historiques.',
                'latitude' => 6.131944,
                'longitude' => 1.222778,
                'categories' => ['Ville', 'Plage', 'Culture', 'Marché'],
                'photos' => [
                    ['https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=80', 'Plage de Lomé au coucher du soleil'],
                    ['https://images.unsplash.com/photo-1526772662000-3f88f10405ff?auto=format&fit=crop&w=1200&q=80', 'Ambiance colorée d’un marché togolais'],
                ],
            ],
            [
                'nom' => 'Aného',
                'region' => 'Maritime',
                'description' => 'Ancienne capitale coloniale, Aného conserve un patrimoine architectural remarquable face à l’océan. Ses ruelles, ses églises et ses maisons afro-brésiliennes racontent plusieurs siècles d’échanges entre l’Europe, le Brésil et les côtes du Golfe de Guinée.',
                'latitude' => 6.228333,
                'longitude' => 1.591944,
                'categories' => ['Patrimoine', 'Plage', 'Culture'],
                'photos' => [
                    ['https://images.unsplash.com/photo-1505228395891-9a51e7e86bf6?auto=format&fit=crop&w=1200&q=80', 'Littoral atlantique près d’Aného'],
                    ['https://images.unsplash.com/photo-1518998053901-5348d3961a04?auto=format&fit=crop&w=1200&q=80', 'Architecture historique de la côte'],
                ],
            ],
            [
                'nom' => 'Togoville',
                'region' => 'Maritime',
                'description' => 'Bordée par le lac Togo, Togoville est une cité chargée d’histoire où furent signés les traités de protectorat. Sa cathédrale, ses cases traditionnelles et ses pirogues colorées en font une étape paisible et authentique.',
                'latitude' => 6.238333,
                'longitude' => 1.483333,
                'categories' => ['Patrimoine', 'Nature', 'Culture'],
                'photos' => [
                    ['https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?auto=format&fit=crop&w=1200&q=80', 'Pirogues sur les rives du lac Togo'],
                    ['https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1200&q=80', 'Village au bord de l’eau'],
                ],
            ],
            [
                'nom' => 'Kpalimé',
                'region' => 'Plateaux',
                'description' => 'Nichée dans les collines verdoyantes du sud-ouest, Kpalimé est la capitale togolaise du café et du cacao. Cascades, plantations, ateliers de batik et sentiers de randonnée en font une destination idéale pour les amoureux de nature.',
                'latitude' => 6.9,
                'longitude' => 0.633333,
                'categories' => ['Montagne', 'Nature', 'Cascade', 'Culture'],
                'photos' => [
                    ['https://images.unsplash.com/photo-1441974231531-c6227db76b6e?auto=format&fit=crop&w=1200&q=80', 'Forêt tropicale autour de Kpalimé'],
                    ['https://images.unsplash.com/photo-1433086966358-54859d0ed716?auto=format&fit=crop&w=1200&q=80', 'Cascade au cœur des Plateaux'],
                ],
            ],
            [
                'nom' => 'Mont Agou',
                'region' => 'Plateaux',
                'description' => 'Point culminant du Togo à 986 mètres, le mont Agou offre des panoramas exceptionnels sur les Plateaux et, par temps clair, jusqu’au lac Volta. Les communautés locales y cultivent le café et accueillent les randonneurs avec générosité.',
                'latitude' => 6.866667,
                'longitude' => 0.75,
                'categories' => ['Montagne', 'Nature'],
                'photos' => [
                    ['https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=1200&q=80', 'Sommet verdoyant du mont Agou'],
                    ['https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=1200&q=80', 'Panorama de montagne'],
                ],
            ],
            [
                'nom' => 'Cascade de Womé',
                'region' => 'Plateaux',
                'description' => 'À quelques kilomètres de Kpalimé, la cascade de Womé se jette dans un bassin naturel entouré d’une végétation luxuriante. Une marche facile à travers les plantations mène à ce lieu rafraîchissant, apprécié des familles et des randonneurs.',
                'latitude' => 6.933333,
                'longitude' => 0.583333,
                'categories' => ['Cascade', 'Nature'],
                'photos' => [
                    ['https://images.unsplash.com/photo-1432405972618-c60b0225b8f9?auto=format&fit=crop&w=1200&q=80', 'Chute d’eau en forêt tropicale'],
                    ['https://images.unsplash.com/photo-1509316975850-ff9c5deb0cd9?auto=format&fit=crop&w=1200&q=80', 'Sous-bois humide des Plateaux'],
                ],
            ],
            [
                'nom' => 'Sokodé',
                'region' => 'Centrale',
                'description' => 'Deuxième ville du pays, Sokodé est le cœur de la culture tem et un carrefour commercial animé. Son grand marché, ses tisserands et l’hospitalité de ses habitants offrent un aperçu vivant du centre du Togo.',
                'latitude' => 8.983333,
                'longitude' => 1.133333,
                'categories' => ['Ville', 'Culture', 'Marché'],
                'photos' => [
                    ['https://images.unsplash.com/photo-1523712999610-f77fbcfc3843?auto=format&fit=crop&w=1200&q=80', 'Paysage vallonné du centre du Togo'],
                    ['https://images.unsplash.com/photo-1526772662000-3f88f10405ff?auto=format&fit=crop&w=1200&q=80', 'Étals colorés du marché'],
                ],
            ],
            [
                'nom' => 'Kara',
                'region' => 'Kara',
                'description' => 'Capitale de la région du même nom, Kara est le pays des Kabyè, réputés pour leur agriculture en terrasses et leurs traditions initiatiques. Les collines environnantes, le marché et les villages alentour invitent à une immersion culturelle.',
                'latitude' => 9.551111,
                'longitude' => 1.186111,
                'categories' => ['Culture', 'Montagne', 'Ville'],
                'photos' => [
                    ['https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=1200&q=80', 'Collines du pays kabyè'],
                    ['https://images.unsplash.com/photo-1489392191049-fc10c97e64b6?auto=format&fit=crop&w=1200&q=80', 'Village et paysage du Nord togolais'],
                ],
            ],
            [
                'nom' => 'Koutammakou',
                'region' => 'Kara',
                'description' => 'Inscrit au patrimoine mondial de l’UNESCO, le pays Koutammakou abrite les célèbres tours en terre crue des Batammariba, appelées takienta. Entre greniers, sanctuaires et paysages de savane, la visite est une rencontre inoubliable avec un mode de vie ancestral.',
                'latitude' => 9.75,
                'longitude' => 1.333333,
                'categories' => ['Patrimoine', 'Culture', 'Nature'],
                'photos' => [
                    ['https://images.unsplash.com/photo-1544735716-392fe2489ffa?auto=format&fit=crop&w=1200&q=80', 'Habitat traditionnel en terre crue'],
                    ['https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=1200&q=80', 'Savane du nord du Togo'],
                ],
            ],
            [
                'nom' => 'Dapaong',
                'region' => 'Savanes',
                'description' => 'Porte d’entrée du nord du Togo, Dapaong séduit par son marché, son musée régional et les paysages de savane qui l’entourent. Les grottes de Nano et les villages gourmantché des environs constituent des étapes culturelles et naturelles remarquables.',
                'latitude' => 10.862222,
                'longitude' => 0.2075,
                'categories' => ['Nature', 'Culture', 'Marché'],
                'photos' => [
                    ['https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=1200&q=80', 'Savane et baobabs des Savanes'],
                    ['https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?auto=format&fit=crop&w=1200&q=80', 'Paysage sec du nord du pays'],
                ],
            ],
        ];

        $destinations = [];

        foreach ($donnees as $donnee) {
            $destination = Destination::create([
                'nom_destination' => $donnee['nom'],
                'description' => $donnee['description'],
                'latitude' => $donnee['latitude'],
                'longitude' => $donnee['longitude'],
                'id_region' => $regions[$donnee['region']]->id_region,
            ]);

            $destination->categories()->sync(
                collect($donnee['categories'])
                    ->map(fn (string $libelle) => $categories[$libelle]->id_categorie)
                    ->all()
            );

            foreach ($donnee['photos'] as [$url, $legende]) {
                Photo::create([
                    'chemin' => $url,
                    'legende' => $legende,
                    'id_destination' => $destination->id_destination,
                ]);
            }

            $destinations[$donnee['nom']] = $destination;
        }

        return $destinations;
    }

    /**
     * @param  array<string, Destination>  $destinations
     * @return array<string, Excursion>
     */
    private function creerExcursions(array $destinations): array
    {
        $donnees = [
            [
                'titre' => 'Lomé entre histoire et océan',
                'destination' => 'Lomé',
                'description' => 'Une demi-journée pour découvrir la capitale autrement : le monument de l’Indépendance, la cathédrale, le quartier de Bè et une pause face à l’océan. Un guide local partage les anecdotes et les secrets de la ville.',
                'prix' => 15000, 'duree' => 4, 'places' => 20, 'date' => now()->addDays(7)->setTime(8, 30),
            ],
            [
                'titre' => 'Marché de Lomé et artisanat',
                'destination' => 'Lomé',
                'description' => 'Parcourez les allées du grand marché et du marché aux fétiches en compagnie d’un guide. Rencontrez les artisans, découvrez les tissus wax et repartez avec un souvenir authentique.',
                'prix' => 12000, 'duree' => 3, 'places' => 15, 'date' => now()->addDays(10)->setTime(9, 0),
            ],
            [
                'titre' => 'Balade sur le lac Togo à Togoville',
                'destination' => 'Togoville',
                'description' => 'Embarquez en pirogue sur le lac Togo jusqu’à Togoville. Visite guidée du village, de la cathédrale et rencontre avec les pêcheurs, entre histoire et douceur de vivre.',
                'prix' => 18000, 'duree' => 5, 'places' => 18, 'date' => now()->addDays(12)->setTime(8, 0),
            ],
            [
                'titre' => 'Sur les pas de l’histoire à Aného',
                'destination' => 'Aného',
                'description' => 'Découvrez l’ancienne capitale coloniale à travers ses maisons afro-brésiliennes, ses églises et son front de mer. Une immersion dans les échanges culturels qui ont façonné la côte togolaise.',
                'prix' => 14000, 'duree' => 4, 'places' => 16, 'date' => now()->addDays(18)->setTime(9, 0),
            ],
            [
                'titre' => 'Randonnée au Mont Agou',
                'destination' => 'Mont Agou',
                'description' => 'Ascension du point culminant du Togo (986 m) à travers forêts et plantations. Panorama grandiose, rencontre avec les producteurs de café et déjeuner local au sommet.',
                'prix' => 20000, 'duree' => 6, 'places' => 12, 'date' => now()->addDays(20)->setTime(7, 0),
            ],
            [
                'titre' => 'Cascades de Womé et caféière',
                'destination' => 'Cascade de Womé',
                'description' => 'Marche facile jusqu’à la cascade de Womé, baignade rafraîchissante puis visite d’une caféière familiale. Dégustation de café et de chocolat artisanal au programme.',
                'prix' => 17000, 'duree' => 5, 'places' => 15, 'date' => now()->addDays(25)->setTime(8, 0),
            ],
            [
                'titre' => 'Kpalimé, nature et artisanat',
                'destination' => 'Kpalimé',
                'description' => 'Une journée entre nature et savoir-faire : sentier botanique, atelier de batik et rencontre avec les artisans de Kpalimé. Déjeuner togolais inclus.',
                'prix' => 16000, 'duree' => 5, 'places' => 20, 'date' => now()->addDays(30)->setTime(8, 30),
            ],
            [
                'titre' => 'Nuit en campement à Kpalimé',
                'destination' => 'Kpalimé',
                'description' => 'Une expérience immersive au cœur des collines : randonnée au coucher du soleil, dîner traditionnel, nuit sous tente et réveil au chant des oiseaux.',
                'prix' => 35000, 'duree' => 24, 'places' => 10, 'date' => now()->addDays(35)->setTime(16, 0),
            ],
            [
                'titre' => 'Rencontre Kabyè à Sokodé',
                'destination' => 'Sokodé',
                'description' => 'Partez à la rencontre du peuple tem et de ses traditions : visite d’un village, démonstration de tissage et repas partagé autour des plats locaux.',
                'prix' => 19000, 'duree' => 6, 'places' => 14, 'date' => now()->addDays(40)->setTime(8, 0),
            ],
            [
                'titre' => 'Marché de Sokodé et tissage',
                'destination' => 'Sokodé',
                'description' => 'Le grand marché de Sokodé comme vous ne l’avez jamais vu : couleurs, épices et tissus. Initiez-vous au tissage traditionnel dans un atelier familial.',
                'prix' => 13000, 'duree' => 4, 'places' => 18, 'date' => now()->addDays(45)->setTime(9, 0),
            ],
            [
                'titre' => 'Les tours Takienta de Koutammakou',
                'destination' => 'Koutammakou',
                'description' => 'Journée au cœur du patrimoine mondial de l’UNESCO : découverte des tours takienta, rencontre des Batammariba et compréhension de leur organisation sociale et spirituelle.',
                'prix' => 25000, 'duree' => 7, 'places' => 12, 'date' => now()->addDays(50)->setTime(7, 30),
            ],
            [
                'titre' => 'Culture Somba et paysages du Nord',
                'destination' => 'Koutammakou',
                'description' => 'Une immersion dans les paysages de savane et la culture somba : greniers, cases en terre, sentiers agricoles et échanges avec les habitants.',
                'prix' => 22000, 'duree' => 6, 'places' => 15, 'date' => now()->addDays(55)->setTime(8, 0),
            ],
            [
                'titre' => 'Kara, collines et traditions',
                'destination' => 'Kara',
                'description' => 'Découverte de Kara et de ses environs : mont Kara, villages kabyè, terrasses agricoles et marché animé. Une journée au rythme du nord du Togo.',
                'prix' => 18000, 'duree' => 5, 'places' => 16, 'date' => now()->addDays(60)->setTime(8, 30),
            ],
            [
                'titre' => 'Fête traditionnelle de Kpalimé',
                'destination' => 'Kpalimé',
                'description' => 'Assistez aux danses et aux célébrations traditionnelles de Kpalimé, en compagnie des habitants. Une expérience culturelle intense, à vivre une fois dans sa vie.',
                'prix' => 15000, 'duree' => 5, 'places' => 20, 'date' => now()->subDays(20)->setTime(10, 0),
            ],
            [
                'titre' => 'Découverte de Lomé en famille',
                'destination' => 'Lomé',
                'description' => 'Un parcours ludique et adapté aux enfants pour découvrir Lomé : plage, parcs, musée et dégustation de spécialités locales. Une sortie conviviale à partager en famille.',
                'prix' => 10000, 'duree' => 3, 'places' => 25, 'date' => now()->subDays(10)->setTime(9, 30),
            ],
        ];

        $excursions = [];

        foreach ($donnees as $index => $donnee) {
            $excursion = Excursion::create([
                'titre' => $donnee['titre'],
                'description' => $donnee['description'],
                'prix' => $donnee['prix'],
                'duree_heures' => $donnee['duree'],
                'places_max' => $donnee['places'],
                'date_excursion' => $donnee['date'],
                'id_destination' => $destinations[$donnee['destination']]->id_destination,
            ]);

            $excursions['e'.($index + 1)] = $excursion;
        }

        return $excursions;
    }

    /**
     * @param  array<string, Utilisateur>  $utilisateurs
     * @param  array<string, Excursion>  $excursions
     */
    private function creerReservations(array $utilisateurs, array $excursions): void
    {
        $donnees = [
            ['utilisateur' => 'touriste', 'excursion' => 'e1', 'personnes' => 2, 'statut' => Reservation::STATUT_CONFIRMEE],
            ['utilisateur' => 'touriste', 'excursion' => 'e5', 'personnes' => 1, 'statut' => Reservation::STATUT_EN_ATTENTE],
            ['utilisateur' => 'awa', 'excursion' => 'e3', 'personnes' => 3, 'statut' => Reservation::STATUT_CONFIRMEE],
            ['utilisateur' => 'awa', 'excursion' => 'e11', 'personnes' => 2, 'statut' => Reservation::STATUT_ANNULEE],
            ['utilisateur' => 'fatou', 'excursion' => 'e2', 'personnes' => 1, 'statut' => Reservation::STATUT_EN_ATTENTE],
            ['utilisateur' => 'touriste', 'excursion' => 'e14', 'personnes' => 2, 'statut' => Reservation::STATUT_TERMINEE],
            ['utilisateur' => 'fatou', 'excursion' => 'e8', 'personnes' => 2, 'statut' => Reservation::STATUT_EN_ATTENTE],
            ['utilisateur' => 'awa', 'excursion' => 'e6', 'personnes' => 2, 'statut' => Reservation::STATUT_CONFIRMEE],
            ['utilisateur' => 'yao', 'excursion' => 'e3', 'personnes' => 1, 'statut' => Reservation::STATUT_CONFIRMEE],
        ];

        foreach ($donnees as $donnee) {
            $excursion = $excursions[$donnee['excursion']];

            Reservation::create([
                'nb_personnes' => $donnee['personnes'],
                'montant' => (float) $excursion->prix * $donnee['personnes'],
                'statut' => $donnee['statut'],
                'id_utilisateur' => $utilisateurs[$donnee['utilisateur']]->id_utilisateur,
                'id_excursion' => $excursion->id_excursion,
            ]);
        }
    }

    /**
     * @param  array<string, Utilisateur>  $utilisateurs
     */
    private function creerMessages(array $utilisateurs): void
    {
        ContactMessage::create([
            'nom' => 'Awa Diallo',
            'email' => 'awa.diallo@example.com',
            'sujet' => 'Excursion privée pour un groupe de six',
            'message' => "Bonjour,\n\nNous préparons un séjour de dix jours au Togo en famille. Serait-il possible d’organiser une excursion privée à Kpalimé pour six personnes, avec un guide francophone ?\n\nMerci d’avance pour votre retour.",
            'lu' => false,
            'id_utilisateur' => $utilisateurs['awa']->id_utilisateur,
        ]);

        ContactMessage::create([
            'nom' => 'Marc Lemaire',
            'email' => 'marc.lemaire@example.com',
            'sujet' => 'Dates de la randonnée au Mont Agou',
            'message' => "Bonjour,\n\nJe souhaite savoir s’il reste des places pour la randonnée au Mont Agou et si un transport depuis Lomé est prévu.\n\nCordialement,",
            'lu' => false,
        ]);

        ContactMessage::create([
            'nom' => 'Fatou Traoré',
            'email' => 'fatou.traore@example.com',
            'sujet' => 'Question sur l’annulation',
            'message' => "Bonjour,\n\nPouvez-vous me confirmer que l’annulation est gratuite jusqu’à 48 heures avant l’excursion ? Merci beaucoup.",
            'lu' => true,
            'id_utilisateur' => $utilisateurs['fatou']->id_utilisateur,
        ]);
    }
}
