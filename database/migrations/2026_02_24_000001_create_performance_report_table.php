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
        Schema::create('performance_report', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ID_Elderly');
            $table->unsignedBigInteger('ID_ADL');
            $table->unsignedBigInteger('ID_TAI');
            $table->unsignedBigInteger('ID_CG');
            $table->unsignedBigInteger('ID_User');
            $table->dateTime('Date');
            $table->string('State');
            $table->string('Activity');
            $table->string('Problems')->nullable();
            $table->string('Relative')->nullable();
            $table->string('Note')->nullable();
            $table->timestamps();

            $table->foreign('ID_Elderly')->references('ID_Elderly')->on('elderlys')->onDelete('cascade');
            $table->foreign('ID_ADL')->references('ID_ADL')->on('barthel_adls')->onDelete('cascade');
            $table->foreign('ID_TAI')->references('id')->on('score_t_a_i_s')->onDelete('cascade');
            $table->foreign('ID_CG')->references('ID_CG')->on('care_givers')->onDelete('cascade');
            $table->foreign('ID_User')->references('ID_User')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_report');
    }
};
