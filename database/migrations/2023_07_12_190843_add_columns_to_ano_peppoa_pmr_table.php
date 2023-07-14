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
        Schema::table('ano_peppoa_pmr', function (Blueprint $table) {
            //Referencia a tabela peppoa_pmr
            $table->integer('peppoa_pmr_id')->unsigned()->nullable();
            $table->foreign('peppoa_pmr_id')->references('id')->on('peppoa_pmr');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ano_peppoa_pmr', function (Blueprint $table) {
            //
        });
    }
};
