<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('score_t_a_i_s', function (Blueprint $table) {
            if (!Schema::hasColumn('score_t_a_i_s', 'ID_ADL')) {
                $table->unsignedBigInteger('ID_ADL')->nullable()->after('ID_Elderly');
                $table->foreign('ID_ADL')->references('ID_ADL')->on('barthel_adls')->onDelete('cascade');
            }
        });
    }

    public function down(): void {
        Schema::table('score_t_a_i_s', function (Blueprint $table) {
            if (Schema::hasColumn('score_t_a_i_s', 'ID_ADL')) {
                $table->dropForeign([$table->getTable() ? 'ID_ADL' : 'ID_ADL']);
                $table->dropColumn('ID_ADL');
            }
        });
    }
};
