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
        if (! Schema::hasColumn('kendaraan_masuks', 'kode_tiket')) {
            Schema::table('kendaraan_masuks', function (Blueprint $table) {
                $table->string('kode_tiket')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('kendaraan_masuks', 'kode_tiket')) {
            Schema::table('kendaraan_masuks', function (Blueprint $table) {
                $table->dropColumn('kode_tiket');
            });
        }
    }
};
