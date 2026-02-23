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
        Schema::create('address_elderlys', function (Blueprint $table) {
            $table->id('ID_Address');
            $table->unsignedBigInteger('ID_Elderly');
            $table->string('Name_Elderly');
            $table->string('Latitude_position')->nullable();
            $table->string('Longitude_position')->nullable();
            $table->foreign('ID_Elderly')->references('ID_Elderly')->on('elderlys')->onDelete('cascade');

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('address_elderlys');
    }
};
