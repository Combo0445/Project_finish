<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Backfills created_at for the DemoSeeder's fictional elderly records so
     * the new date filter has something realistic to demonstrate on --
     * these rows predate the created_at column and would otherwise show as
     * unknown ("-").
     */
    public function up(): void
    {
        DB::table('elderlys')
            ->whereIn('Name_Elderly', ['สมชาย ใจดี', 'สมหญิง รักษ์ดี'])
            ->update(['created_at' => '2026-08-18 09:00:00']);

        DB::table('elderlys')
            ->where('Name_Elderly', 'มานะ ตั้งใจ')
            ->update(['created_at' => '2026-08-20 09:00:00']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('elderlys')
            ->whereIn('Name_Elderly', ['สมชาย ใจดี', 'สมหญิง รักษ์ดี', 'มานะ ตั้งใจ'])
            ->update(['created_at' => null]);
    }
};
