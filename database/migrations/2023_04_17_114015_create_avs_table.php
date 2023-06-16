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
        Schema::create('avs', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('dataCriacao');//É setado automaticamente no Back-end
            $table->string('prioridade')->nullable();
            $table->string('banco')->nullable();
            $table->string('agencia')->nullable();
            $table->string('conta')->nullable();
            $table->string('pix')->nullable();
            $table->string('comentario')->nullable();
            $table->string('status');//É setado automaticamente no Back-end

            //Campos que serão preenchidos após a criação das rotas
            $table->float('valorExtraReais')->nullable();
            $table->float('valorExtraDolar')->nullable();
            $table->string('justificativaValorExtra')->nullable();
            
            //Campos que serão preenchidos após a viagem
            $table->string('contatos')->nullable();
            $table->string('atividades')->nullable();
            $table->string('conclusoes')->nullable();

            $table->string('outroObjetivo')->nullable();

            //------------------------------------------------------------------

            //Referencia a tabela de Usuário
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users');

            //Referencia a tabela objetivos
            $table->integer('objetivo_id')->unsigned()->nullable();
            $table->foreign('objetivo_id')->references('id')->on('objetivos');

            $table->float('valorReais')->nullable();
            $table->float('valorDolar')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avs');
    }
};
