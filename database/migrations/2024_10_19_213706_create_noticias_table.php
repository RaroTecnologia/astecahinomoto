<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNoticiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('noticias', function (Blueprint $table) {
            $table->id(); // ID automático da notícia
            $table->string('titulo'); // Título da notícia
            $table->string('slug')->unique(); // Slug da notícia para URL
            $table->text('conteudo'); // Conteúdo da notícia
            $table->string('imagem')->nullable(); // Imagem da notícia (opcional)
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade'); // Relacionamento com categorias
            $table->timestamp('publicado_em')->nullable(); // Data de publicação
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
        Schema::dropIfExists('noticias');
    }
}
