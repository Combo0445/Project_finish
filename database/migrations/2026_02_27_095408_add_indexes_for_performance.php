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
        Schema::table('elderlys', function (Blueprint $table) {
            $table->index('Name_Elderly');
        });

        Schema::table('barthel_adls', function (Blueprint $table) {
            $table->index('created_at');
        });

        Schema::table('care_givers', function (Blueprint $table) {
            $table->index('Date_CG');
        });

        Schema::table('activity_caregivers', function (Blueprint $table) {
            $table->index('Date_ACG');
        });

        Schema::table('score_t_a_i_s', function (Blueprint $table) {
            $table->index('updated_at'); // Used in TAIController whereDate
        });

        Schema::table('care_instructions', function (Blueprint $table) {
            $table->index('Date_CI');
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->index(['model_type', 'action']); // Composite index
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('elderlys', function (Blueprint $table) {
            $table->dropIndex(['Name_Elderly']);
        });

        Schema::table('barthel_adls', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });

        Schema::table('care_givers', function (Blueprint $table) {
            $table->dropIndex(['Date_CG']);
        });

        Schema::table('activity_caregivers', function (Blueprint $table) {
            $table->dropIndex(['Date_ACG']);
        });

        Schema::table('score_t_a_i_s', function (Blueprint $table) {
            $table->dropIndex(['updated_at']);
        });

        Schema::table('care_instructions', function (Blueprint $table) {
            $table->dropIndex(['Date_CI']);
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropIndex(['model_type', 'action']);
        });
    }
};
