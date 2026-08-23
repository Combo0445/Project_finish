<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Backfills created_at for the DemoSeeder's three fictional elderly
     * records, matched by ID_Elderly rather than name -- these rows predate
     * the created_at column and would otherwise show as unknown ("-").
     * Replaces an earlier name-matched version of this same backfill.
     */
    public function up(): void
    {
        // Clean up the superseded name-matched migration's row so the
        // migrations table doesn't reference a file that no longer exists
        DB::table('migrations')
            ->where('migration', '2026_08_23_063337_backfill_demo_elderly_created_at')
            ->delete();

        DB::table('elderlys')
            ->whereIn('ID_Elderly', [1, 2])
            ->update(['created_at' => '2026-08-18 09:00:00']);

        DB::table('elderlys')
            ->where('ID_Elderly', 3)
            ->update(['created_at' => '2026-08-20 09:00:00']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('elderlys')
            ->whereIn('ID_Elderly', [1, 2, 3])
            ->update(['created_at' => null]);
    }
};
