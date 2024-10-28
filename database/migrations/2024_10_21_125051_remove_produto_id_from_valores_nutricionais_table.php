<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNivelToCategoriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categorias', function (Blueprint $table) {
            // Adiciona a coluna 'nivel' à tabela categorias, com um valor padrão de 'marca'
            $table->enum('nivel', ['marca', 'produto', 'linha'])->default('marca')->after('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categorias', function (Blueprint $table) {
            // Remove a coluna 'nivel' se a migration for revertida
            $table->dropColumn('nivel');
        });
    }
}
