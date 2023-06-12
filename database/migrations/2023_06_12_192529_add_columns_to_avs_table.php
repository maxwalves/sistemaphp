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
        Schema::table('avs', function (Blueprint $table) {
            $table->string('nome_municipio')->nullable();
            $table->integer('municipio_id')->nullable();
            $table->integer('numero_projeto')->nullable();
            $table->integer('numero_lote')->nullable();
            $table->integer('numero_medicao')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('avs', function (Blueprint $table) {
            //
        });
    }
};
