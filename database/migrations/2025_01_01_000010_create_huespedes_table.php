<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('huespedes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 255);
            $table->string('cedula', 20)->unique();
            $table->tinyInteger('edad')->unsigned()->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('email', 255)->nullable()->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index('cedula');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('huespedes');
    }
};
