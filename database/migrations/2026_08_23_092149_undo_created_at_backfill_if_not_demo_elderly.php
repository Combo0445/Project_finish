<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Corrective follow-up to 2026_08_23_063902_backfill_demo_elderly_created_at_by_id,
     * which matched by hardcoded ID_Elderly (1, 2 -> 2026-08-18; 3 -> 2026-08-20)
     * instead of by name. That was safe locally, where those IDs are the
     * DemoSeeder's fictional elderly, but production has never run that
     * seeder -- ID 3 doesn't exist there at all, and a later migration
     * that assumed the same IDs crashed production's deploy trying to
     * reference it. Critically, IDs 1 and 2 *do* exist on production as
     * real, unrelated elderly, meaning that earlier migration silently
     * overwrote a real person's created_at with a fake demo date.
     *
     * This re-checks ID_Elderly 1 and 2 (2 doesn't need checking, per
     * above): if the row's Name_Elderly isn't the exact demo name that
     * migration assumed, it was a real elderly wrongly touched -- reset
     * to "now" rather than back to NULL, so it keeps the property the
     * remaining-elderly backfill migration was for (findable by the
     * created-date filter) without carrying a fabricated, wrong date.
     */
    public function up(): void
    {
        $wronglyAssumed = [
            1 => 'สมชาย ใจดี',
            2 => 'สมหญิง รักษ์ดี',
            3 => 'มานะ ตั้งใจ',
        ];

        foreach ($wronglyAssumed as $elderlyId => $expectedName) {
            $row = DB::table('elderlys')->where('ID_Elderly', $elderlyId)->first();

            if ($row && $row->Name_Elderly !== $expectedName) {
                DB::table('elderlys')
                    ->where('ID_Elderly', $elderlyId)
                    ->update(['created_at' => now(), 'updated_at' => now()]);
            }
        }
    }

    /**
     * Not reversible: there's no record of what these rows' created_at
     * was before the mistaken backfill to restore.
     */
    public function down(): void
    {
    }
};
