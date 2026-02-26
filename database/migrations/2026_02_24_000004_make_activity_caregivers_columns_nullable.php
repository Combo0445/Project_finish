<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration 
{
    public function up(): void
    {
        $table = DB::getTablePrefix() . 'activity_caregivers';

        $columns = [
            'Evaluate', 'Dress_the_wound', 'Rehabilitate', 'Clean_body', 'Take_care_medicine',
            'Take_care_feeding', 'Environmental', 'Take_exercise', 'Give_advice_consult',
            'Take_to_see_a_doctor', 'Other', 'Take_to_make_merit', 'Take_to_market',
            'Take_to_meet_friends', 'Take_to_allowance', 'Talk_as_friends', 'Other_specified',
            'Problem'
        ];

        foreach ($columns as $col) {
            DB::statement("ALTER TABLE `{$table}` MODIFY `{$col}` VARCHAR(255) NULL");
        }

        // Add Solution column if it's missing
        if (!Schema::hasColumn('activity_caregivers', 'Solution')) {
            Schema::table('activity_caregivers', function (Blueprint $table) {
                $table->string('Solution')->nullable()->after('Problem');
            });
        }
    }

    public function down(): void
    {
        Schema::table('activity_caregivers', function (Blueprint $table) {
            $table->dropColumn('Solution');
        });

        $table = DB::getTablePrefix() . 'activity_caregivers';

        $columns = [
            'Evaluate', 'Dress_the_wound', 'Rehabilitate', 'Clean_body', 'Take_care_medicine',
            'Take_care_feeding', 'Environmental', 'Take_exercise', 'Give_advice_consult',
            'Take_to_see_a_doctor', 'Other', 'Take_to_make_merit', 'Take_to_market',
            'Take_to_meet_friends', 'Take_to_allowance', 'Talk_as_friends', 'Other_specified',
            'Problem'
        ];

        foreach ($columns as $col) {
            DB::statement("ALTER TABLE `{$table}` MODIFY `{$col}` VARCHAR(255) NOT NULL");
        }
    }
};
