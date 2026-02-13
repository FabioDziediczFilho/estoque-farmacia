<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Professor Explica: Usamos hasColumn para evitar erros se a migração 
        // for rodada novamente em um banco que já possui os campos (comum em dev).
        if (!Schema::hasColumn('movimentacoes', 'responsavel')) {
            Schema::table('movimentacoes', function (Blueprint $table) {
                $table->string('responsavel')->nullable()->after('observacao');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movimentacoes', function (Blueprint $table) {
            $table->dropColumn('responsavel');
        });
    }
};
