<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('produtos', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('categoria_id');
        });

        Schema::table('skus', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('produto_id');
        });
    }

    public function down()
    {
        Schema::table('produtos', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });

        Schema::table('skus', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};