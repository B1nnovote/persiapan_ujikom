<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kompensasi extends Model
{
    protected $table = 'kompensasi';

    protected $fillable = [
        'id_kendaraan_masuk',
        'jumlah',
        'status',
        'nama_pemilik',
        'bukti_foto',
        'keterangan',
        'diajukan_pada',
        'diproses_pada',
        'catatan_admin',
    ];


    public function kendaraanMasuk()
    {
        return $this->belongsTo(KendaraanMasuk::class, 'id_kendaraan_masuk');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_kompensasi');
    }
}
