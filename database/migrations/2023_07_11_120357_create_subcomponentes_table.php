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
        Schema::create('subcomponentes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome');
            //Referencia a tabela componentes
            $table->integer('componente_id')->unsigned()->nullable();
            $table->foreign('componente_id')->references('id')->on('componentes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subcomponentes');
    }
};
