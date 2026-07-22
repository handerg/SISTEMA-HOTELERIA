<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('huesped_id')->constrained('huespedes')->restrictOnDelete();
            $table->foreignId('habitacion_id')->constrained('habitaciones')->restrictOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->decimal('precio_acordado', 10, 2); // Snapshot inmutable del precio
            $table->enum('estado', ['activa', 'finalizada', 'cancelada'])->default('activa');
            $table->text('notas')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('estado');
            $table->index(['fecha_inicio', 'fecha_fin']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
