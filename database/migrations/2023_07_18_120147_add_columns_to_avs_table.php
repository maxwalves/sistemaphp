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
            $table->integer('horasExtras')->nullable();
            $table->integer('minutosExtras')->nullable();
            $table->string('justificativaHorasExtras')->nullable();
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
