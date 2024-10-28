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
        Schema::create('valores_nutricionais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produto_id')->constrained()->onDelete('cascade');
            $table->foreignId('sku_id')->nullable()->constrained()->onDelete('cascade');  // Se for para um SKU específico
            $table->foreignId('nutriente_id')->constrained('nutrientes')->onDelete('cascade');
            $table->decimal('valor_por_100g', 8, 2)->nullable();
            $table->decimal('valor_por_porção', 8, 2)->nullable();
            $table->decimal('valor_diario', 5, 2)->nullable(); // %VD
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('valores_nutricionais');
    }
};
