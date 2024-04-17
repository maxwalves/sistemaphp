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
        Schema::table('anexo_rotas', function (Blueprint $table) {
            $table->integer('av_id')->unsigned()->nullable();
            $table->foreign('av_id')->references('id')->on('avs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anexo_rotas', function (Blueprint $table) {
            $table->dropForeign(['av_id']);
            $table->dropColumn('av_id');
        });
    }
};
