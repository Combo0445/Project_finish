<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * The created_at column was added retroactively, so real (non-demo)
     * elderly records added before this rollout have no true creation date
     * to recover -- their created_at is still NULL at this point (the demo
     * migration only ever touched 3 specific IDs). Left as-is, filtering by
     * ANY date range excludes them entirely, since they'd never fall inside
     * (or after) whatever range is picked -- which looks exactly like the
     * feature is broken to anyone with real, non-demo elderly on file.
     *
     * Backfilling them to "now" (this rollout's timestamp) makes them show
     * up under today's date going forward, rather than being permanently
     * unreachable by the filter -- the same "treat rollout time as the
     * known start of tracking" approach used when adding created_at to any
     * existing table.
     */
    public function up(): void
    {
        DB::table('elderlys')
            ->whereNull('created_at')
            ->update(['created_at' => now(), 'updated_at' => now()]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not reversible: rows already had NULL created_at before this ran,
        // and there's no record of which ones so they could be reset.
    }
};
