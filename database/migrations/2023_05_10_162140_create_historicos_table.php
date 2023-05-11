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
        Schema::create('historicos', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('dataOcorrencia');
            $table->string('tipoOcorrencia');
            $table->text('comentario')->nullable();
            $table->string('perfilDonoComentario')->nullable();

            $table->bigInteger('usuario_id')->unsigned()->nullable();
            $table->foreign('usuario_id')->references('id')->on('users');

            $table->bigInteger('usuario_comentario_id')->unsigned()->nullable();
            $table->foreign('usuario_comentario_id')->references('id')->on('users');

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
        Schema::dropIfExists('historicos');
    }
};
