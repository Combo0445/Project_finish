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
        Schema::create('care_instructions', function (Blueprint $table) {
            $table->id('ID_CI');
            $table->unsignedBigInteger('ID_Elderly');
            $table->date('Date_CI');
            $table->string('Name_Elderly');
            $table->string('Name_Doctor');
            $table->string('Name_Staff');
            $table->string('Confirm')->nullable();
            $table->string('Care_instructions');

            $table->foreign('ID_Elderly')->references('ID_Elderly')->on('elderlys')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('care_instructions');
    }
};
