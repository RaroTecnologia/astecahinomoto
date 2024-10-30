<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('home_banners', function (Blueprint $table) {
            $table->string('imagem_mobile')->nullable()->after('imagem');
            $table->renameColumn('imagem', 'imagem_desktop');
        });
    }

    public function down()
    {
        Schema::table('home_banners', function (Blueprint $table) {
            $table->dropColumn('imagem_mobile');
            $table->renameColumn('imagem_desktop', 'imagem');
        });
    }
};
