<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Gives the DemoSeeder's three fictional elderly records a map pin so
     * the dashboard's location map has something to demonstrate. Their
     * stored Address text is a placeholder ("ตำบลตัวอย่าง อำเภอตัวอย่าง
     * จังหวัดตัวอย่าง"), not a real place, so it can't be geocoded --
     * these are representative points around Buriram instead, matching
     * the dashboard map's own default center.
     *
     * IMPORTANT: matched by exact Name_Elderly, not by hardcoded ID_Elderly
     * -- a prior version of this migration assumed IDs 1/2/3 (as seeded
     * locally) and crashed production with a foreign key violation because
     * production's real elderlys table doesn't have those IDs at all (it
     * has never run the local-only DemoSeeder). Worse, if it had, IDs 1/2
     * did resolve to *some* row there and would have silently overwritten
     * a real elderly's address record with a fake demo name/location.
     * Looking the row up by name and skipping entirely when no match
     * exists makes this a true no-op on any environment without the
     * DemoSeeder's data, which is exactly what production needs it to be.
     */
    public function up(): void
    {
        $demoCoordinates = [
            'สมชาย ใจดี' => ['lat' => '14.9930', 'lng' => '103.1029'],
            'สมหญิง รักษ์ดี' => ['lat' => '15.0050', 'lng' => '103.1150'],
            'มานะ ตั้งใจ' => ['lat' => '14.9800', 'lng' => '103.0900'],
        ];

        foreach ($demoCoordinates as $name => $coords) {
            $elderlyId = DB::table('elderlys')->where('Name_Elderly', $name)->value('ID_Elderly');

            if (!$elderlyId) {
                continue;
            }

            DB::table('address_elderlys')->updateOrInsert(
                ['ID_Elderly' => $elderlyId],
                [
                    'Name_Elderly' => $name,
                    'Latitude_position' => $coords['lat'],
                    'Longitude_position' => $coords['lng'],
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $names = ['สมชาย ใจดี', 'สมหญิง รักษ์ดี', 'มานะ ตั้งใจ'];

        DB::table('address_elderlys')
            ->whereIn('ID_Elderly', DB::table('elderlys')->whereIn('Name_Elderly', $names)->pluck('ID_Elderly'))
            ->update(['Latitude_position' => null, 'Longitude_position' => null]);
    }
};
