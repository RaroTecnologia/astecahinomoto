<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipoProdutoTabela extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::table('categorias', function (Blueprint $table) {
            // Adiciona a coluna tipo_id e define como chave estrangeira
            $table->foreignId('tipo_id')->nullable()->constrained('tipos')->onDelete('set null');
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
            $table->dropForeign(['tipo_id']);
            $table->dropColumn('tipo_id');
        });

        Schema::dropIfExists('tipos');
    }
}
