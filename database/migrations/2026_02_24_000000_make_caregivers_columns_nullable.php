<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Use raw ALTER statements to avoid requiring doctrine/dbal for change()
        $table = DB::getTablePrefix() . 'care_givers';

        $strings = [
            'Name_CG','Related','Phone_CG','Name_Elderly','Address','Group_ADL','Disease','Disability','Rights',
            'Consciousness','Vital_signs','Bedsores','Pain','Swelling','Itchy_rash','Stiff_joints','Malnutrition',
            'Eating','Swallowing','Defecation','Urinary_excretion','Taking_medicine','Emotional_state',
            'Economic_problems','Social_problems','Doctor_FU','Other_problems','Assistance','Reporter'
        ];

        foreach ($strings as $col) {
            DB::statement("ALTER TABLE `{$table}` MODIFY `{$col}` VARCHAR(255) NULL");
        }

        // Dates
        DB::statement("ALTER TABLE `{$table}` MODIFY `Birthday` DATE NULL");
        DB::statement("ALTER TABLE `{$table}` MODIFY `Date_CG` DATE NULL");

        // Integers
        DB::statement("ALTER TABLE `{$table}` MODIFY `Weight` INT NULL");
        DB::statement("ALTER TABLE `{$table}` MODIFY `Height` INT NULL");
        DB::statement("ALTER TABLE `{$table}` MODIFY `Waist` INT NULL");
    }

    public function down(): void
    {
        $table = DB::getTablePrefix() . 'care_givers';

        // Revert to NOT NULL where possible (will fail if nulls exist). Use with caution.
        $strings = [
            'Name_CG','Related','Phone_CG','Name_Elderly','Address','Group_ADL','Disease','Disability','Rights',
            'Consciousness','Vital_signs','Bedsores','Pain','Swelling','Itchy_rash','Stiff_joints','Malnutrition',
            'Eating','Swallowing','Defecation','Urinary_excretion','Taking_medicine','Emotional_state',
            'Economic_problems','Social_problems','Doctor_FU','Other_problems','Assistance','Reporter'
        ];

        foreach ($strings as $col) {
            DB::statement("ALTER TABLE `{$table}` MODIFY `{$col}` VARCHAR(255) NOT NULL");
        }

        DB::statement("ALTER TABLE `{$table}` MODIFY `Birthday` DATE NOT NULL");
        DB::statement("ALTER TABLE `{$table}` MODIFY `Date_CG` DATE NOT NULL");

        DB::statement("ALTER TABLE `{$table}` MODIFY `Weight` INT NOT NULL");
        DB::statement("ALTER TABLE `{$table}` MODIFY `Height` INT NOT NULL");
        DB::statement("ALTER TABLE `{$table}` MODIFY `Waist` INT NOT NULL");
    }
};
