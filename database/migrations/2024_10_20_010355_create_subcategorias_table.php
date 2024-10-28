<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubcategoriasTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subcategorias', function (Blueprint $table) {
            $table->id(); // ID da subcategoria
            $table->foreignId('categoria_id')->constrained()->onDelete('cascade'); // Relacionamento com a tabela categorias
            $table->string('nome'); // Nome da subcategoria (ex: Shoyu)
            $table->string('slug')->unique(); // Slug para URL amigável
            $table->timestamps(); // Timestamps padrão (created_at, updated_at)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subcategorias');
    }
}
