<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->id('id_photo');
            $table->string('chemin', 255);
            $table->string('legende', 150)->nullable();
            $table->foreignId('id_destination')
                ->constrained('destinations', 'id_destination')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};
