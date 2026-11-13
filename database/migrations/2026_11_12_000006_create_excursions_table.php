<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('excursions', function (Blueprint $table) {
            $table->id('id_excursion');
            $table->string('titre', 150);
            $table->text('description');
            $table->decimal('prix', 10, 2);
            $table->unsignedSmallInteger('duree_heures');
            $table->unsignedSmallInteger('places_max');
            $table->dateTime('date_excursion');
            $table->foreignId('id_destination')
                ->constrained('destinations', 'id_destination')
                ->restrictOnDelete();
            $table->index('date_excursion');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('excursions');
    }
};
