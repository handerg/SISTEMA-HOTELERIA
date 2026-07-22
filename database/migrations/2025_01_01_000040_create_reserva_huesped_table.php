<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reserva_huesped', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reserva_id')->constrained('reservas')->cascadeOnDelete();
            $table->foreignId('huesped_id')->constrained('huespedes')->restrictOnDelete();
            $table->timestamps();

            $table->unique(['reserva_id', 'huesped_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reserva_huesped');
    }
};
