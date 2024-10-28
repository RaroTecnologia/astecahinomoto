<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('informacoes_sku', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sku_id')->constrained()->onDelete('cascade');
            $table->integer('porcoes_por_embalagem');
            $table->decimal('valor_por_porção', 8, 2);  // Valor por porção (ex: ml ou gramas)
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('informacoes_sku');
    }
};
