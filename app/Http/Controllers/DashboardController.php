<?php
namespace App\Http\Controllers;

use App\Models\DataKendaraan;
use App\Models\KendaraanKeluar;
use App\Models\KendaraanMasuk;
use App\Models\Keuangan;
use App\Models\Pembayaran;
use App\Models\StokLahan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function adminDashboard()
    {
        $data = $this->getDashboardData();
        return view('backend.index', $data);
    }

    public function petugasDashboard()
    {
        $data = $this->getDashboardData();
        return view('frontend.index', $data);
    }

    private function getDashboardData()
    {
        $user = Auth::user();

        $totalMasuk = KendaraanMasuk::where('status_parkir', 'sedang parkir')->count();
        // $totalMasuk     = KendaraanMasuk::count();
        $totalKeluar    = KendaraanKeluar::count();
        $totalKendaraan = DataKendaraan::count();

        $stokMobil = StokLahan::where('jenis_kendaraan', 'mobil')->first();
        $stokMotor = StokLahan::where('jenis_kendaraan', 'motor')->first();

        $sisaMobil = $stokMobil ? $stokMobil->total_slot - $stokMobil->terpakai : 0;
        $sisaMotor = $stokMotor ? $stokMotor->total_slot - $stokMotor->terpakai : 0;

        $totalTunai = Pembayaran::where('pembayaran', 'tunai')->sum('total');
        $totalQris  = Pembayaran::where('pembayaran', 'qris')->sum('total');

// === Grafik kendaraan masuk per bulan ===
        $masukPerBulan = KendaraanMasuk::select(
            DB::raw("COUNT(*) as total"),
            DB::raw("MONTH(created_at) as bulan")
        )
            ->whereYear('created_at', now()->year)
            ->groupBy(DB::raw("MONTH(created_at)"))
            ->orderBy(DB::raw("MONTH(created_at)"))
            ->get();

        $labels    = [];
        $dataMasuk = [];

        for ($i = 1; $i <= 12; $i++) {
            $labels[]    = date('F', mktime(0, 0, 0, $i, 1));
            $found       = $masukPerBulan->firstWhere('bulan', $i);
            $dataMasuk[] = $found ? $found->total : 0;
        }

// === Grafik Keuangan ===
        $keuangan = Keuangan::select(
            DB::raw("MONTH(waktu_transaksi) as bulan"),
            DB::raw("SUM(CASE WHEN jenis_transaksi = 'pemasukan' THEN jumlah ELSE 0 END) as pemasukan"),
            DB::raw("SUM(CASE WHEN jenis_transaksi = 'pengeluaran' THEN jumlah ELSE 0 END) as pengeluaran")
        )
            ->whereYear('waktu_transaksi', now()->year)
            ->groupBy(DB::raw("MONTH(waktu_transaksi)"))
            ->orderBy(DB::raw("MONTH(waktu_transaksi)"))
            ->get();

        $dataPemasukan   = [];
        $dataPengeluaran = [];
        foreach (range(1, 12) as $i) {
            $bulanData         = $keuangan->firstWhere('bulan', $i);
            $dataPemasukan[]   = $bulanData ? (int) $bulanData->pemasukan : 0;
            $dataPengeluaran[] = $bulanData ? (int) $bulanData->pengeluaran : 0;
        }

        $data = [
            'totalMasuk'      => $totalMasuk,
            'totalKeluar'     => $totalKeluar,
            'totalKendaraan'  => $totalKendaraan,
            'sisaMobil'       => $sisaMobil,
            'sisaMotor'       => $sisaMotor,
            'labels'          => $labels,
            'dataMasuk'       => $dataMasuk,
            'dataPemasukan'   => $dataPemasukan,
            'dataPengeluaran' => $dataPengeluaran,
            'totalTunai'      => $totalTunai,
            'totalQris'       => $totalQris,

        ];
        return $data;

    }

    // public function index()
    // {
    //     $user = Auth::user();

    // $totalMasuk     = KendaraanMasuk::count();
    // $totalKeluar    = KendaraanKeluar::count();
    // $totalKendaraan = DataKendaraan::count();

    // $stokMobil = StokLahan::where('jenis_kendaraan', 'mobil')->first();
    // $stokMotor = StokLahan::where('jenis_kendaraan', 'motor')->first();

    // $sisaMobil = $stokMobil ? $stokMobil->total_slot - $stokMobil->terpakai : 0;
    // $sisaMotor = $stokMotor ? $stokMotor->total_slot - $stokMotor->terpakai : 0;

    // // === Grafik kendaraan masuk per bulan ===
    // $masukPerBulan = KendaraanMasuk::select(
    //     DB::raw("COUNT(*) as total"),
    //     DB::raw("MONTH(created_at) as bulan")
    // )
    //     ->whereYear('created_at', now()->year)
    //     ->groupBy(DB::raw("MONTH(created_at)"))
    //     ->orderBy(DB::raw("MONTH(created_at)"))
    //     ->get();

    // $labels    = [];
    // $dataMasuk = [];

    // for ($i = 1; $i <= 12; $i++) {
    //     $labels[]    = date('F', mktime(0, 0, 0, $i, 1));
    //     $found       = $masukPerBulan->firstWhere('bulan', $i);
    //     $dataMasuk[] = $found ? $found->total : 0;
    // }

    // // === Grafik Keuangan ===
    // $keuangan = Keuangan::select(
    //     DB::raw("MONTH(waktu_transaksi) as bulan"),
    //     DB::raw("SUM(CASE WHEN jenis_transaksi = 'pemasukan' THEN jumlah ELSE 0 END) as pemasukan"),
    //     DB::raw("SUM(CASE WHEN jenis_transaksi = 'pengeluaran' THEN jumlah ELSE 0 END) as pengeluaran")
    // )
    //     ->whereYear('waktu_transaksi', now()->year)
    //     ->groupBy(DB::raw("MONTH(waktu_transaksi)"))
    //     ->orderBy(DB::raw("MONTH(waktu_transaksi)"))
    //     ->get();

    // $dataPemasukan   = [];
    // $dataPengeluaran = [];
    // foreach (range(1, 12) as $i) {
    //     $bulanData         = $keuangan->firstWhere('bulan', $i);
    //     $dataPemasukan[]   = $bulanData ? (int) $bulanData->pemasukan : 0;
    //     $dataPengeluaran[] = $bulanData ? (int) $bulanData->pengeluaran : 0;
    // }

    // $data = [
    //     'totalMasuk'      => $totalMasuk,
    //     'totalKeluar'     => $totalKeluar,
    //     'totalKendaraan'  => $totalKendaraan,
    //     'sisaMobil'       => $sisaMobil,
    //     'sisaMotor'       => $sisaMotor,
    //     'labels'          => $labels,
    //     'dataMasuk'       => $dataMasuk,
    //     'dataPemasukan'   => $dataPemasukan,
    //     'dataPengeluaran' => $dataPengeluaran,
    // ];

    // if ($user->role == 1) {
    //     return view('backend.index', $data);
    // } else {
    //     return view('frontend.index', $data);
    // }
    // }

}
