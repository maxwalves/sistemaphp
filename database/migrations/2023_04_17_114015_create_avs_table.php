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
            $table->integer('banco')->nullable();
            $table->integer('agencia')->nullable();
            $table->integer('conta')->nullable();
            $table->string('pix')->nullable();
            $table->string('comentario')->nullable();
            $table->string('status');//É setado automaticamente no Back-end

            //Campos que serão preenchidos após a criação das rotas
            $table->integer('valorExtra')->nullable();
            $table->string('justificativaValorExtra')->nullable();
            
            //Campos que serão preenchidos após a viagem
            $table->string('contatos')->nullable();
            $table->string('atividades')->nullable();
            $table->string('conclusoes')->nullable();

            //Veículo Próprio--------------------------------------------------
            $table->boolean('isVeiculoProprioAutorizado')->nullable();
            $table->dateTime('dataAutorizacaoVeiculoProprio')->nullable();
            $table->string('assinaturaDiretoriaExecutiva')->nullable();

            $table->bigInteger('usuarioDiretoriaExecutiva')->unsigned()->nullable();
            $table->foreign('usuarioDiretoriaExecutiva')->references('id')->on('users');// Vai referenciar o usuário da Diretoria Executiva

            $table->string('outroObjetivo')->nullable();

            //------------------------------------------------------------------

            //Referencia a tabela de Usuário
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users');

            //Referencia a tabela objetivos
            $table->integer('objetivo_id')->unsigned()->nullable();
            $table->foreign('objetivo_id')->references('id')->on('objetivos');

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
