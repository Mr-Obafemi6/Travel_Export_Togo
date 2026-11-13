<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id('id_reservation');
            $table->unsignedSmallInteger('nb_personnes');
            $table->decimal('montant', 10, 2);
            $table->enum('statut', ['en_attente', 'confirmee', 'annulee', 'terminee'])
                ->default('en_attente');
            $table->dateTime('date_reservation')->useCurrent();
            $table->foreignId('id_utilisateur')
                ->constrained('utilisateurs', 'id_utilisateur')
                ->restrictOnDelete();
            $table->foreignId('id_excursion')
                ->constrained('excursions', 'id_excursion')
                ->restrictOnDelete();
            $table->index('statut');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
