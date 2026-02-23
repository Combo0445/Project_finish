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
        Schema::create('barthel_adls', function (Blueprint $table) {
            $table->id('ID_ADL');
            $table->unsignedBigInteger('ID_Elderly');
            $table->string('Name_Elderly');
            $table->unsignedBigInteger('ID_User');
            $table->string('Name_User');
            $table->integer('Score_ADL');
            $table->string('Group_ADL');
            $table->integer('Feeding');
            $table->integer('Grooming');
            $table->integer('Transfer');
            $table->integer('Toilet_use');
            $table->integer('Mobility');
            $table->integer('Dressing');
            $table->integer('Stairs');
            $table->integer('Bathing');
            $table->integer('Bowels');
            $table->integer('Bladder');
            $table->foreign('ID_Elderly')->references('ID_Elderly')->on('elderlys')->onDelete('cascade');
            $table->foreign('ID_User')->references('ID_User')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barthel_adls');
    }
};
