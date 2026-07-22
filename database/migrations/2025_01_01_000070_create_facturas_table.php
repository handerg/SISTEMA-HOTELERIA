<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reserva_id')->constrained('reservas')->restrictOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->decimal('subtotal_habitacion', 10, 2);
            $table->decimal('subtotal_consumos', 10, 2);
            $table->decimal('total', 10, 2);
            $table->dateTime('fecha_emision');
            $table->text('notas')->nullable();
            $table->timestamps();
            // NO softDeletes — las facturas son inmutables
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
