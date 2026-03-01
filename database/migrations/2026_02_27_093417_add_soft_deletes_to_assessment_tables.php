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
        $tables = [
            'barthel_adls',
            'care_givers',
            'activity_caregivers',
            'score_t_a_i_s',
            'care_instructions',
            'performance_report'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                // Check if column doesn't exist to prevent errors
                if (!Schema::hasColumn($table->getTable(), 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'barthel_adls',
            'care_givers',
            'activity_caregivers',
            'score_t_a_i_s',
            'care_instructions',
            'performance_report'
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (Schema::hasColumn($tableName, 'deleted_at')) {
                    $table->dropSoftDeletes();
                }
            });
        }
    }
};
