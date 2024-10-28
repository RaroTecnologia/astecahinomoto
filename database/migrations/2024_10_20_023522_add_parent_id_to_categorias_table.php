<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParentIdToCategoriasTable extends Migration
{
    public function up()
    {
        Schema::table('categorias', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->constrained('categorias')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('categorias', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });
    }
}
