<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCategoriaForeignKeyInProdutosTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            // Primeiro, removemos a chave estrangeira antiga
            $table->dropForeign(['categoria_id']);

            // Agora, criamos a nova relação correta com a tabela categorias
            $table->foreign('categoria_id')->references('id')->on('categorias')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            // Reverter para a chave antiga se necessário
            $table->dropForeign(['categoria_id']);
            $table->foreign('categoria_id')->references('id')->on('subcategorias')->onDelete('cascade');
        });
    }
}
