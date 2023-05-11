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
        Schema::table('avs', function (Blueprint $table) {
            $table->boolean('isAprovadoGestor')->default(false);
            $table->boolean('isAprovadoCarroDiretoriaExecutiva')->default(false);
            $table->boolean('isAprovadoViagemInternacional')->default(false);
            $table->boolean('isRealizadoReserva')->default(false);
            $table->boolean('isAprovadoFinanceiro')->default(false);
            $table->boolean('isReservadoVeiculoProprio')->default(false);
            $table->boolean('isPrestacaoContasRealizada')->default(false);
            $table->boolean('isFinanceiroAprovouPC')->default(false);
            $table->boolean('isGestorAprovouPC')->default(false);
            $table->boolean('isAcertoContasRealizado')->default(false);

            $table->foreignId('usuario_gestor')->nullable()->constrained('users');
            $table->foreignId('usuario_diretoria')->nullable()->constrained('users');
            $table->foreignId('usuario_secretaria')->nullable()->constrained('users');
            $table->foreignId('usuario_financeiro')->nullable()->constrained('users');
            $table->foreignId('usuario_frota')->nullable()->constrained('users');
            $table->foreignId('usuario_financeiro_pc')->nullable()->constrained('users');
            $table->foreignId('usuario_gestor_pc')->nullable()->constrained('users');
            $table->foreignId('usuario_fin_ac')->nullable()->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('avs', function (Blueprint $table) {
            //
        });
    }
};
