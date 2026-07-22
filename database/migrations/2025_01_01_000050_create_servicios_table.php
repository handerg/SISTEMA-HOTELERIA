<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 255);
            $table->decimal('precio', 10, 2);
            $table->integer('stock')->unsigned()->default(0);
            $table->string('categoria', 100)->nullable(); // minibar, restaurante, lavandería
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('categoria');
            $table->index('activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};
