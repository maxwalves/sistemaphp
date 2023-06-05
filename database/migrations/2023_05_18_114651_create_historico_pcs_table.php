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
        Schema::create('historico_pcs', function (Blueprint $table) {
            $table->increments('id');
            $table->float('valorReais')->nullable();
            $table->float('valorDolar')->nullable();
            $table->string('ocorrencia')->nullable();
            $table->string('comentario')->nullable();
            $table->string('anexoRelatorio')->nullable();

            //Referencia a tabela de AV
            $table->integer('av_id')->unsigned()->nullable();
            $table->foreign('av_id')->references('id')->on('avs');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historico_pcs');
    }
};
