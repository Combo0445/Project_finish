<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('elderlys', function (Blueprint $table) {
            $table->id('ID_Elderly');
            $table->string('Name_Elderly');
            $table->date('Birthday');
            $table->text('Address');
            $table->string('Phone_Elderly');
            $table->string('Image_Elderly')->nullable();
        });
    }

    public function down(): void {
        Schema::dropIfExists('elderlys');
    }
};
