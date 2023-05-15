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
        Schema::create('anexo_rotas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('anexoHotel')->nullable();
            $table->string('anexoTransporte')->nullable();

            $table->bigInteger('usuario_id')->unsigned()->nullable();
            $table->foreign('usuario_id')->references('id')->on('users');

            $table->integer('rota_id')->unsigned()->nullable();
            $table->foreign('rota_id')->references('id')->on('rotas');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anexo_rotas');
    }
};
