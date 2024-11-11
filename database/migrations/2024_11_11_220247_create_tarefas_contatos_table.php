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
        Schema::create('tarefas_contatos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contato_id')->constrained('contatos')->cascadeOnDelete();
            $table->foreignId('criado_por')->constrained('users');
            $table->foreignId('atribuido_para')->nullable()->constrained('users')->nullOnDelete();
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->enum('prioridade', ['baixa', 'media', 'alta'])->default('media');
            $table->enum('status', ['pendente', 'em_andamento', 'concluida', 'cancelada'])
                ->default('pendente');
            $table->timestamp('data_vencimento')->nullable();
            $table->timestamp('concluido_em')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarefas_contatos');
    }
};
