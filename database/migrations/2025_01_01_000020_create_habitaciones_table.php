<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('habitaciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('tipo', 50); // simple, doble, suite
            $table->decimal('precio_base', 10, 2);
            $table->enum('estado', ['disponible', 'ocupada', 'mantenimiento'])->default('disponible');
            $table->string('foto_path', 500)->nullable();
            $table->text('descripcion')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('estado');
            $table->index('tipo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('habitaciones');
    }
};
