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
        Schema::create('score_t_a_i_s', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ID_Elderly');
            $table->unsignedBigInteger('ID_User');
            $table->integer('mobility')->nullable();
            $table->integer('confuse')->nullable();
            $table->integer('feed')->nullable();
            $table->integer('toilet')->nullable();
            $table->string('group')->nullable();
            $table->foreign('ID_Elderly')->references('ID_Elderly')->on('elderlys')->onDelete('cascade');
            $table->foreign('ID_User')->references('ID_User')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('score_t_a_i_s');
    }
};
