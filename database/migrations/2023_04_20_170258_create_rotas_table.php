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
        Schema::create('rotas', function (Blueprint $table) {
            $table->increments('id');

            $table->boolean('isViagemInternacional')->nullable();

            $table->string('estadoOrigemNacional')->nullable();
            $table->string('cidadeOrigemNacional')->nullable();
            $table->string('estadoDestinoNacional')->nullable();
            $table->string('cidadeDestinoNacional')->nullable();

            $table->string('continenteOrigemInternacional')->nullable();
            $table->string('paisOrigemInternacional')->nullable();
            $table->string('estadoOrigemInternacional')->nullable();
            $table->string('cidadeOrigemInternacional')->nullable();

            $table->string('continenteDestinoInternacional')->nullable();
            $table->string('paisDestinoInternacional')->nullable();
            $table->string('estadoDestinoInternacional')->nullable();
            $table->string('cidadeDestinoInternacional')->nullable();


            $table->dateTime('dataHoraSaida')->nullable();
            $table->dateTime('dataHoraChegada')->nullable();

            $table->boolean('isReservaHotel')->nullable();
            
            $table->boolean('isOnibusLeito')->nullable();
            $table->boolean('isOnibusConvencional')->nullable();
            $table->boolean('isVeiculoProprio')->nullable();
            $table->boolean('isVeiculoEmpresa')->nullable();
            $table->boolean('isAereo')->nullable();


            //Referencia a tabela AV
            $table->integer('av_id')->unsigned()->nullable();
            $table->foreign('av_id')->references('id')->on('avs');

            //Referencia a tabela Veículo Próprio
            $table->integer('veiculoProprio_id')->unsigned()->nullable();
            $table->foreign('veiculoProprio_id')->references('id')->on('veiculo_proprios');

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
        Schema::dropIfExists('rotas');
    }
};
