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
        Schema::create('contatos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('email');
            $table->string('telefone')->nullable();
            $table->string('cidade')->nullable();
            $table->string('departamento');
            $table->text('mensagem');
            $table->enum('status', ['novo', 'em_andamento', 'respondido', 'finalizado', 'arquivado'])
                ->default('novo');
            $table->text('notas_internas')->nullable();
            $table->foreignId('atribuido_para')->nullable()
                ->constrained('users')->nullOnDelete();
            $table->timestamp('ultima_interacao')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contatos');
    }
};
