<?php

namespace App\Console\Commands;

use App\Models\Reservation;
use Illuminate\Console\Command;

class CloturerReservations extends Command
{
    protected $signature = 'reservations:cloturer';

    protected $description = 'Termine les réservations confirmées et annule les demandes en attente dont l’excursion est passée';

    public function handle(): int
    {
        $terminees = Reservation::where('statut', Reservation::STATUT_CONFIRMEE)
            ->whereHas('excursion', fn ($query) => $query->where('date_excursion', '<=', now()))
            ->update(['statut' => Reservation::STATUT_TERMINEE]);

        $annulees = Reservation::where('statut', Reservation::STATUT_EN_ATTENTE)
            ->whereHas('excursion', fn ($query) => $query->where('date_excursion', '<=', now()))
            ->update(['statut' => Reservation::STATUT_ANNULEE]);

        $this->info("{$terminees} réservation(s) terminée(s), {$annulees} demande(s) en attente annulée(s).");

        return self::SUCCESS;
    }
}
