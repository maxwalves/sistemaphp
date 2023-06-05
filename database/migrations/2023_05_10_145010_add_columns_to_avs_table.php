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
