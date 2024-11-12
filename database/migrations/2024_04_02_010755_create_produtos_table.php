<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutosTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id(); // ID do produto
            $table->foreignId('subcategoria_id')->constrained()->onDelete('cascade'); // Relacionamento com a tabela subcategorias
            $table->string('nome'); // Nome do produto (ex: Hinomoto Light Shoyu)
            $table->text('descricao')->nullable(); // Descrição do produto
            $table->string('imagem')->nullable(); // URL da imagem do produto
            $table->string('slug')->unique(); // Slug para URL amigável
            $table->text('informacao_nutricional'); // Informações nutricionais (pode ser JSON)
            $table->timestamps(); // Campos de data de criação e atualização
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produtos');
    }
}
