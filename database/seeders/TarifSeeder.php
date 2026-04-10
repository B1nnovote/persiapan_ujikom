<?php
namespace Database\Seeders;

use App\Models\Tarif;
use DB;
use Illuminate\Database\Seeder;

class TarifSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tarifs')->delete();

        Tarif::create([
            'jenis_kendaraan' => 'motor',
            'jenis_tarif'     => 'biasa',
            'tarif'           => 2000,
        ]);

        Tarif::create([
            'jenis_kendaraan' => 'mobil',
            'jenis_tarif'     => 'biasa',
            'tarif'           => 5000,
        ]);
    }
}
