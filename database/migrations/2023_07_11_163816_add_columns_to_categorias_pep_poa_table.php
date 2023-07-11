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
        Schema::table('categorias_pep_poa', function (Blueprint $table) {
            //Referencia a tabela subcomponentes
            $table->integer('subcomponente_id')->unsigned()->nullable();
            $table->foreign('subcomponente_id')->references('id')->on('subcomponentes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categorias_pep_poa', function (Blueprint $table) {
            //
        });
    }
};
