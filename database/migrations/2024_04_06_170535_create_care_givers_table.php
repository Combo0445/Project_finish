<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('care_givers', function (Blueprint $table) {
            $table->id('ID_CG');
            $table->unsignedBigInteger('ID_ADL');
            $table->unsignedBigInteger('ID_Elderly');
            $table->string('Name_CG');
            $table->string('Related');
            $table->string('Phone_CG');
            $table->string('Name_Elderly');
            $table->date('Birthday');
            $table->integer('Weight');
            $table->integer('Height');
            $table->integer('Waist');
            $table->string('Address');
            $table->string('Group_ADL');
            $table->string('Disease');
            $table->string('Disability');
            $table->string('Rights');
            $table->date('Date_CG');
            $table->string('Consciousness');
            $table->string('Vital_signs');
            $table->string('Bedsores');
            $table->string('Pain');
            $table->string('Swelling');
            $table->string('Itchy_rash');
            $table->string('Stiff_joints');
            $table->string('Malnutrition');
            $table->string('Eating');
            $table->string('Swallowing');
            $table->string('Defecation');
            $table->string('Urinary_excretion');
            $table->string('Taking_medicine');
            $table->string('Emotional_state');
            $table->string('Economic_problems');
            $table->string('Social_problems');
            $table->string('Doctor_FU');
            $table->string('Other_problems');
            $table->string('Assistance');
            $table->string('Reporter');
            $table->foreign('ID_ADL')->references('ID_ADL')->on('barthel_adls')->onDelete('cascade');
            $table->foreign('ID_Elderly')->references('ID_Elderly')->on('elderlys')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('care_givers');
    }
};
