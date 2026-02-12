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
        Schema::create('lotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produto_id')->constrained('produtos')->onDelete('cascade');
            $table->string('numero_lote');
            $table->date('data_validade')->nullable();
            $table->integer('quantidade_inicial');
            $table->integer('quantidade_atual');
            $table->date('data_entrada');
            $table->timestamps();
            
            $table->index(['produto_id', 'numero_lote']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lotes');
    }
};
