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
        Schema::create('users', function (Blueprint $table) {
            $table->id('ID_User');
            $table->string('Username');
            $table->string('Password');
            $table->unsignedBigInteger('ID_Personnel');
            $table->string('Type_Personnel');
            $table->string('Name_User')->nullable();
            $table->string('Type_Doctor')->nullable();
            $table->string('Email')->unique()->nullable();
            $table->text('Address')->nullable();
            $table->text('Phone')->nullable();
            $table->string('Image_User')->nullable();


            $table->foreign('ID_Personnel')->references('ID_Personnel')->on('personnels')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
