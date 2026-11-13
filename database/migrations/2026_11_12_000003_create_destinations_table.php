<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('destinations', function (Blueprint $table) {
            $table->id('id_destination');
            $table->string('nom_destination', 120);
            $table->text('description');
            $table->decimal('latitude', 9, 6)->nullable();
            $table->decimal('longitude', 9, 6)->nullable();
            $table->foreignId('id_region')
                ->constrained('regions', 'id_region')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('destinations');
    }
};
