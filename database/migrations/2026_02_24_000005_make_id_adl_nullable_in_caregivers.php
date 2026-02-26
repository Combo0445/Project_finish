<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $table = DB::getTablePrefix() . 'care_givers';
        DB::statement("ALTER TABLE `{$table}` MODIFY `ID_ADL` BIGINT UNSIGNED NULL");
    }

    public function down(): void
    {
        $table = DB::getTablePrefix() . 'care_givers';
        DB::statement("ALTER TABLE `{$table}` MODIFY `ID_ADL` BIGINT UNSIGNED NOT NULL");
    }
};
