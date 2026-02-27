<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('care_instruction_id');
            $table->unsignedBigInteger('medicine_id');
            $table->integer('amount');
            $table->string('dosage')->nullable(); // วิธีรับประทาน
            $table->boolean('dispensed')->default(false); // จ่ายยาแล้วหรือยัง
            $table->timestamps();

            // Should add foreign key to care_instructions table, but its primary key is ID_CI
            $table->foreign('care_instruction_id')->references('ID_CI')->on('care_instructions')->onDelete('cascade');
            $table->foreign('medicine_id')->references('id')->on('medicines')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
