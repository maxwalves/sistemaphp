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
            $table->dateTime('dataCriacao');
            $table->string('prioridade')->nullable();
            $table->integer('banco')->nullable();
            $table->integer('agencia')->nullable();
            $table->integer('conta')->nullable();
            $table->string('pix')->nullable();
            $table->string('comentario')->nullable();
            $table->string('status');
            $table->integer('valorExtra')->nullable();
            $table->string('justificativaValorExtra')->nullable();
            $table->boolean('isVeiculoProprio')->nullable();
            $table->boolean('isVeiculoEmpresa')->nullable();
            $table->string('contatos')->nullable();
            $table->string('atividades')->nullable();
            $table->string('conclusoes')->nullable();

            //Veículo Próprio--------------------------------------------------
            $table->boolean('isVeiculoProprioAutorizado')->nullable();
            $table->dateTime('dataAutorizacaoVeiculoProprio')->nullable();
            $table->string('assinaturaDiretoriaExecutiva')->nullable();
            $table->integer('usuarioDiretoriaExecutiva')->nullable(); // Vai referenciar o usuário da Diretoria Executiva
            
            //Referencia a tabela Veículo Próprio
            $table->integer('veiculoProprio_id')->unsigned()->nullable();
            $table->foreign('veiculoProprio_id')->references('id')->on('veiculo_proprios');
            //------------------------------------------------------------------

            //Referencia a tabela de Usuário
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users');

            //Referencia a tabela objetivos
            $table->integer('objetivo_id')->unsigned()->nullable();
            $table->foreign('objetivo_id')->references('id')->on('objetivos');
            
            $table->string('outroObjetivo')->nullable();

            //Referencia a tabela veículos Paranacidade
            $table->integer('veiculoParanacidade_id')->unsigned()->nullable();
            $table->foreign('veiculoParanacidade_id')->references('id')->on('veiculo_paranacidades');

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
