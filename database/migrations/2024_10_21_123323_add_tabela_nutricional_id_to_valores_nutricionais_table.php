<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTabelaNutricionalIdToValoresNutricionaisTable extends Migration
{
    public function up()
    {
        Schema::table('valores_nutricionais', function (Blueprint $table) {
            $table->foreignId('tabela_nutricional_id')->constrained('tabelas_nutricionais')->onDelete('cascade'); // Chave estrangeira para a tabela nutricional
        });
    }

    public function down()
    {
        Schema::table('valores_nutricionais', function (Blueprint $table) {
            $table->dropForeign(['tabela_nutricional_id']);
            $table->dropColumn('tabela_nutricional_id');
        });
    }
}
