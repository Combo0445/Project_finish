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
     * the dashboard map's own default center. This also had to insert
     * rather than only update: ID_Elderly 2 and 3 never had an
     * address_elderlys row at all (only 1 did), on top of every row's
     * Latitude_position/Longitude_position being null from the
     * Lat/Lng field-name bug that was just fixed.
     */
    public function up(): void
    {
        $demoCoordinates = [
            1 => ['name' => 'สมชาย ใจดี', 'lat' => '14.9930', 'lng' => '103.1029'],
            2 => ['name' => 'สมหญิง รักษ์ดี', 'lat' => '15.0050', 'lng' => '103.1150'],
            3 => ['name' => 'มานะ ตั้งใจ', 'lat' => '14.9800', 'lng' => '103.0900'],
        ];

        foreach ($demoCoordinates as $elderlyId => $coords) {
            DB::table('address_elderlys')->updateOrInsert(
                ['ID_Elderly' => $elderlyId],
                [
                    'Name_Elderly' => $coords['name'],
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
        DB::table('address_elderlys')
            ->whereIn('ID_Elderly', [1, 2, 3])
            ->update(['Latitude_position' => null, 'Longitude_position' => null]);
    }
};
