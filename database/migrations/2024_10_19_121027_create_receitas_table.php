<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceitasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receitas', function (Blueprint $table) {
            $table->id(); // ID automático da receita
            $table->foreignId('categoria_id')->constrained()->onDelete('cascade'); // Chave estrangeira da categoria
            $table->string('nome'); // Nome da receita
            $table->string('slug')->unique(); // Slug da receita, que será usado na URL
            $table->text('ingredientes'); // Lista de ingredientes da receita
            $table->text('modo_preparo'); // Modo de preparo da receita
            $table->timestamps(); // Campos de data de criação e atualização
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receitas');
    }
}
