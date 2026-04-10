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
        Schema::create('kendaraan_masuks', function (Blueprint $table) {
            $table->id();
            $table->timestamp('waktu_masuk');
            $table->string('kode_tiket')->unique();
            $table->enum('status_parkir', ['sedang parkir', 'sudah keluar'])->default('sedang parkir');
            $table->foreignId('id_kendaraan')->constrained('data_kendaraans')->onDelete('cascade');
            $table->timestamps();
        });
        
        
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kendaraan_masuks');
    }
};
