<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categorie_destination', function (Blueprint $table) {
            $table->foreignId('id_destination')
                ->constrained('destinations', 'id_destination')
                ->cascadeOnDelete();
            $table->foreignId('id_categorie')
                ->constrained('categories', 'id_categorie')
                ->cascadeOnDelete();
            $table->primary(['id_destination', 'id_categorie']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categorie_destination');
    }
};
