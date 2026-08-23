<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('elderlys', function (Blueprint $table) {
            $table->string('ID_Card', 13)->nullable()->after('Name_Elderly');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('elderlys', function (Blueprint $table) {
            $table->dropColumn('ID_Card');
        });
    }
};
