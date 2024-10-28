<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('tabelas_nutricionais', function (Blueprint $table) {
            $table->id();
            $table->string('nome'); // Nome da tabela nutricional
            $table->timestamps();
        });

        // Tabela de relacionamento entre Tabela Nutricional e Nutriente
        Schema::create('nutriente_tabela_nutricional', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tabela_nutricional_id')->constrained('tabelas_nutricionais')->onDelete('cascade');
            $table->foreignId('nutriente_id')->constrained('nutrientes')->onDelete('cascade');
            $table->decimal('valor_por_100g', 8, 2)->nullable();
            $table->decimal('valor_por_porção', 8, 2)->nullable();
            $table->decimal('valor_diario', 5, 2)->nullable(); // %VD
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('nutriente_tabela_nutricional');
        Schema::dropIfExists('tabelas_nutricionais');
    }
};
