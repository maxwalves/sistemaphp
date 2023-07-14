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
        Schema::create('ano_peppoa_pmr', function (Blueprint $table) {
            $table->increments('id');

            //Referencia a tabela anos
            $table->integer('ano_id')->unsigned()->nullable();
            $table->foreign('ano_id')->references('id')->on('anos');

            $table->string('justificativaNaoAtingimento')->nullable();

            $table->integer('metaFisicaBid')->nullable();
            $table->string('unidadeMedidaBid')->nullable();
            $table->integer('metaFisicaPrcid')->nullable();
            $table->string('unidadeMedidaPrcid')->nullable();
            $table->integer('metaFinanceiraBid')->nullable();
            $table->integer('metaFinanceiraPrcid')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ano_peppoa_pmr');
    }
};
