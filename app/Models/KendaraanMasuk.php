<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KendaraanMasuk extends Model
{
    protected $table = 'kendaraan_masuks';

    protected $fillable = [

        'waktu_masuk',
        'status_parkir',
        'id_kendaraan',
        'kode_tiket',
    ];

    protected $casts = [
        'waktu_masuk' => 'datetime',
    ];

    public function dataKendaraan()
    {
        return $this->belongsTo(DataKendaraan::class, 'id_kendaraan');
    }

    public function kompensasi()
    {
        return $this->hasOne(Kompensasi::class, 'id_kendaraan_masuk');
    }

    public function kendaraanKeluar()
    {
        return $this->hasOne(KendaraanKeluar::class, 'id_kendaraan_masuk');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_kendaraan_masuk');
    }

    public static function generateKodeTiket($jenis)
    {
        $today = \Carbon\Carbon::now()->format('Ymd');

        // hitung kendaraan masuk hari ini berdasarkan jenis
        $countToday = self::whereDate('waktu_masuk', \Carbon\Carbon::today())
            ->whereHas('dataKendaraan', function ($q) use ($jenis) {
                $q->where('jenis_kendaraan', $jenis);
            })->count();

        $number = str_pad($countToday + 1, 4, '0', STR_PAD_LEFT);
        $prefix = $jenis == 'motor' ? 'MTR' : 'MBL';

        return $prefix . '-' . $today . '-' . $number;
    }

}
