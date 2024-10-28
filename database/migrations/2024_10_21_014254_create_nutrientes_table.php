<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('nutrientes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('unidade_medida');  // Ex: g, mg, kcal
            $table->string('tipo_nutriente');  // Ex: Macronutriente, Vitamina, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nutrientes');
    }
};
