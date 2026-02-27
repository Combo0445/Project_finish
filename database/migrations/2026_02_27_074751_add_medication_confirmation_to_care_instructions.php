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
        Schema::table('care_instructions', function (Blueprint $table) {
            $table->string('Confirm_Medication')->nullable()->after('Confirm');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('care_instructions', function (Blueprint $table) {
            $table->dropColumn('Confirm_Medication');
        });
    }
};
