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
        Schema::create('peppoa_pmr', function (Blueprint $table) {
            $table->increments('id');

            //Referencia a tabela categorias_pep_poa
            $table->integer('categoriaPeppoa_id')->unsigned()->nullable();
            $table->foreign('categoriaPeppoa_id')->references('id')->on('categorias_pep_poa');

            //Referencia a tabela categoriaPmr_id
            $table->integer('categoriaPmr_id')->unsigned()->nullable();
            $table->foreign('categoriaPmr_id')->references('id')->on('categorias_pmr');

            $table->string('codigoBid')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peppoa_pmr');
    }
};
