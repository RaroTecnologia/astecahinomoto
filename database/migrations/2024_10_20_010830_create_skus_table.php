<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkusTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('skus', function (Blueprint $table) {
            $table->id(); // ID do SKU
            $table->foreignId('produto_id')->constrained()->onDelete('cascade'); // Relacionamento com a tabela produtos
            $table->string('volume'); // Volume do SKU (ex: 150ml, 500ml, etc.)
            $table->decimal('preco', 10, 2)->nullable(); // Preço do SKU (opcional)
            $table->string('codigo_sku')->unique(); // Código único para o SKU (pode ser o código de barras)
            $table->timestamps(); // Campos de data de criação e atualização
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skus');
    }
}
