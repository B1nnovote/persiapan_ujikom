<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Models\DataKendaraan;
use App\Models\KendaraanKeluar;
use App\Models\KendaraanMasuk;
use App\Models\Keuangan;
use App\Models\Pembayaran;
use App\Models\StokLahan;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function adminDashboard()
    {
        return view('backend.index', $this->getDashboardData());
    }

    public function petugasDashboard()
    {
        return view('frontend.index', $this->getDashboardData());
    }

    private function getDashboardData()
    {
        /* ================== DATA BOX ================== */
        $totalMasuk     = KendaraanMasuk::where('status_parkir', 'sedang parkir')->count();
        $totalKeluar    = KendaraanKeluar::count();
        $totalKendaraan = DataKendaraan::count();

        $stokMobil = StokLahan::where('jenis_kendaraan', 'mobil')->first();
        $stokMotor = StokLahan::where('jenis_kendaraan', 'motor')->first();

        $sisaMobil = $stokMobil ? $stokMobil->total_slot - $stokMobil->terpakai : 0;
        $sisaMotor = $stokMotor ? $stokMotor->total_slot - $stokMotor->terpakai : 0;

        $totalTunai = Pembayaran::where('pembayaran', 'tunai')->sum('total');
        $totalQris  = Pembayaran::where('pembayaran', 'qris')->sum('total');

        /* ================== GRAFIK KENDARAAN MASUK (BULANAN FIX) ================== */
        $masuk = KendaraanMasuk::select(
            DB::raw('MONTH(created_at) as bulan'),
            DB::raw('COUNT(*) as total')
        )
            ->whereYear('created_at', now()->year)
            ->groupBy('bulan')
            ->get();

        $labelsMasuk = [];
        $dataMasuk   = [];

        for ($i = 1; $i <= 12; $i++) {
            $labelsMasuk[] = date('F', mktime(0, 0, 0, $i, 1));
            $found         = $masuk->firstWhere('bulan', $i);
            $dataMasuk[]   = $found ? $found->total : 0;
        }

        /* ================== GRAFIK KEUANGAN (DINAMIS) ================== */
        /* ================== GRAFIK KEUANGAN ================== */
        $range = request('range', 'bulanan');

        $query = Keuangan::selectRaw('
    SUM(CASE WHEN jenis_transaksi="pemasukan" THEN jumlah ELSE 0 END) as pemasukan,
    SUM(CASE WHEN jenis_transaksi="pengeluaran" THEN jumlah ELSE 0 END) as pengeluaran
');

        if ($range === 'harian') {
            $query->addSelect(DB::raw('DATE(waktu_transaksi) as label'))
                ->groupBy('label')
                ->orderBy('label');
        } elseif ($range === 'mingguan') {
            $query->addSelect(DB::raw('WEEK(waktu_transaksi) as label'))
                ->groupBy('label')
                ->orderBy('label');
        } elseif ($range === 'tahunan') {
            $query->addSelect(DB::raw('YEAR(waktu_transaksi) as label'))
                ->groupBy('label')
                ->orderBy('label');
        } else {
            // BULANAN
            $query->addSelect(DB::raw('MONTH(waktu_transaksi) as label'))
                ->groupBy('label')
                ->orderBy('label');
        }

        $keuangan = $query->get();

        $labelsKeuangan  = $keuangan->pluck('label')->toArray();
        $dataPemasukan   = $keuangan->pluck('pemasukan')->toArray();
        $dataPengeluaran = $keuangan->pluck('pengeluaran')->toArray();

/* label biar manusiawi */
        if ($range === 'bulanan') {
            $labelsKeuangan = array_map(
                fn($b) => date('F', mktime(0, 0, 0, $b, 1)),
                $labelsKeuangan
            );
        } elseif ($range === 'mingguan') {
            $labelsKeuangan = array_map(
                fn($m) => 'Minggu ke-' . $m,
                $labelsKeuangan
            );
        }

        /* ================== GRAFIK PETUGAS (KHUSUS PETUGAS) ================== */
        $grafikPetugas = [];

        if (Auth::check() && Auth::user()->isAdmin == 0) {
            $grafikPetugas = Pembayaran::select(
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw('SUM(total) as total')
            )
                ->where('id_petugas', Auth::id())
                ->groupBy('tanggal')
                ->orderBy('tanggal')
                ->get();
        }

        /* ================== RETURN ================== */
        return [
            'totalMasuk'      => $totalMasuk,
            'totalKeluar'     => $totalKeluar,
            'totalKendaraan'  => $totalKendaraan,
            'sisaMobil'       => $sisaMobil,
            'sisaMotor'       => $sisaMotor,
            'totalTunai'      => $totalTunai,
            'totalQris'       => $totalQris,

            // grafik kendaraan
            'labelsMasuk'     => $labelsMasuk,
            'dataMasuk'       => $dataMasuk,

            // grafik keuangan
            'labelsKeuangan'  => $labelsKeuangan,
            'dataPemasukan'   => $dataPemasukan,
            'dataPengeluaran' => $dataPengeluaran,

             // grafik petugas 
            'grafikPetugas' => $grafikPetugas,

        ];
    }
}
