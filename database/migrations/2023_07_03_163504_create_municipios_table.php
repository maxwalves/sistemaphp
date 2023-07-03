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
        Schema::create('municipios', function (Blueprint $table) {
            $table->increments('executor');
            $table->string('nomeSam')->nullable();
            $table->string('nomeDSS')->nullable();
            $table->string('assMun')->nullable();
            $table->string('erprcid')->nullable();
            $table->string('cnpj')->nullable();
            $table->string('IBGE')->nullable();
            $table->string('cdCredorSefa')->nullable();
            $table->string('cdTSE')->nullable();
            $table->string('regiaoMetrop')->nullable();
            $table->string('mrae')->nullable();
            $table->string('orgaoProtocolo')->nullable();
            $table->string('localPendencia')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('municipios');
    }
};
