<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_caregivers', function (Blueprint $table) {
            $table->id('ID_ACG');
            $table->unsignedBigInteger('ID_CG');
            $table->date('Date_ACG');
            $table->string('Evaluate');
            $table->string('Dress_the_wound');
            $table->string('Rehabilitate');
            $table->string('Clean_body');
            $table->string('Take_care_medicine');
            $table->string('Take_care_feeding');
            $table->string('Environmental');
            $table->string('Take_exercise');
            $table->string('Give_advice_consult');
            $table->string('Take_to_see_a_doctor');
            $table->string('Other');
            $table->string('Take_to_make_merit');
            $table->string('Take_to_market');
            $table->string('Take_to_meet_friends');
            $table->string('Take_to_allowance');
            $table->string('Talk_as_friends');
            $table->string('Other_specified');
            $table->string('Problem');
            $table->foreign('ID_CG')->references('ID_CG')->on('care_givers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_caregivers');
    }
};
