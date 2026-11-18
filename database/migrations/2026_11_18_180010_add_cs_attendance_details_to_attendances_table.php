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
        Schema::table('attendances', function (Blueprint $table) {
            $table->string('foto_masuk')->nullable()->after('clock_out');
            $table->string('foto_pulang')->nullable()->after('foto_masuk');
            $table->text('lokasi_masuk')->nullable()->after('foto_pulang');
            $table->text('lokasi_pulang')->nullable()->after('lokasi_masuk');
            $table->string('status_masuk')->nullable()->default('Hadir')->after('lokasi_pulang');
            $table->string('status_pulang')->nullable()->after('status_masuk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn([
                'foto_masuk',
                'foto_pulang',
                'lokasi_masuk',
                'lokasi_pulang',
                'status_masuk',
                'status_pulang',
            ]);
        });
    }
};
