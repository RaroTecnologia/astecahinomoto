<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraFieldsToReceitasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('receitas', function (Blueprint $table) {
            $table->integer('compartilhamentos')->default(0); // Contagem de compartilhamento
            $table->integer('curtidas')->default(0); // Contagem de curtidas
            $table->enum('dificuldade', ['fácil', 'médio', 'difícil'])->default('fácil'); // Dificuldade
            $table->string('tempo_preparo')->nullable(); // Tempo de preparo
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('receitas', function (Blueprint $table) {
            $table->dropColumn('compartilhamentos');
            $table->dropColumn('curtidas');
            $table->dropColumn('dificuldade');
            $table->dropColumn('tempo_preparo');
        });
    }
}
