<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('movimentacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lote_id')->constrained('lotes')->onDelete('cascade');
            $table->enum('tipo', ['entrada', 'saida']);
            $table->integer('quantidade');
            $table->dateTime('data_movimentacao');
            $table->text('observacao')->nullable();
            $table->timestamps();
            
            $table->index(['lote_id', 'tipo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimentacoes');
    }
};
