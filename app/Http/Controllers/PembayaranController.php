<?php
namespace App\Http\Controllers;

use App\Models\KendaraanKeluar;
// use Auth;
use App\Models\KendaraanMasuk;
use App\Models\Keuangan;
use App\Models\Pembayaran;
use App\Models\Tarif;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use PDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembayaran::with(['kendaraanMasuk.dataKendaraan', 'kendaraanKeluar', 'kompensasi', 'petugas']);

        if ($request->filled('petugas')) {
            $nama = strtolower($request->petugas);
            $query->whereHas('petugas', function ($q) use ($nama) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$nama}%"]);
            });
        }

        if ($request->filled('pembayaran')) {
            $query->where('pembayaran', $request->pembayaran);
        }

        $data         = $query->latest()->paginate(10);
        $semuaPetugas = User::where('isAdmin', 0)->orderBy('name')->get();

        return view('backend.pembayaran.index', compact('data', 'semuaPetugas'));
    }

    public function create($idKeluar)
    {
        $kendaraanKeluar = KendaraanKeluar::findOrFail($idKeluar);
        $masuk           = KendaraanMasuk::findOrFail($kendaraanKeluar->id_kendaraan_masuk);

        $jamMasuk  = Carbon::parse($masuk->waktu_masuk);
        $jamKeluar = Carbon::parse($kendaraanKeluar->waktu_keluar);

        $jenisKendaraan = $masuk->dataKendaraan->jenis_kendaraan ?? 'motor';

        $tarif = Tarif::where('jenis_kendaraan', $jenisKendaraan)
            ->where('jenis_tarif', 'biasa')
            ->first();

        $total = $tarif ? $tarif->tarif : 0;

        $statusKondisi = strtolower($kendaraanKeluar->status_kondisi);

        if (str_contains($statusKondisi, 'karcis hilang')) {
            $total += 10000;
        } elseif (str_contains($statusKondisi, 'merusak')) {
            $total += 50000;
        }

        return view('backend.pembayaran.create', [
            'keluar' => $kendaraanKeluar,
            'masuk'  => $masuk,
            'total'  => $total,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kendaraan_masuk'  => 'required|exists:kendaraan_masuks,id',
            'id_kendaraan_keluar' => 'required|exists:kendaraan_keluars,id',

            'pembayaran'          => 'required|in:tunai,qris,gratis',
            'total'               => 'required|numeric|min:0',
            'id_kompensasi'       => 'nullable|exists:kompensasis,id',
        ]);

        $pembayaran = Pembayaran::create([
            'id_kendaraan_masuk'  => $request->id_kendaraan_masuk,
            'id_kendaraan_keluar' => $request->id_kendaraan_keluar,
            'id_kompensasi'       => $request->id_kompensasi,
            'id_petugas'          => Auth::id(),
            'total'               => $request->total,
            'pembayaran'          => $request->pembayaran,
        ]);

        $pembayaran->load('kendaraanKeluar');

        $statusKondisi = $pembayaran->kendaraanKeluar->status_kondisi;

        // === Catat pemasukan utama ===
        $pembayaran->update(['total' => $request->total]);

        Keuangan::create([
            'id_pembayaran'   => $pembayaran->id,
            'jumlah'          => $request->total,
            'keterangan'      => $this->tentukanJenisPemasukan($statusKondisi),
            'jenis_transaksi' => 'pemasukan',
            'waktu_transaksi' => now(),
        ]);

        // === Catat kompensasi (pengeluaran) jika ada dan sudah disetujui ===
        if ($request->filled('id_kompensasi')) {
            $kompensasi = Kompensasi::find($request->id_kompensasi);
            if ($kompensasi && $kompensasi->status === 'disetujui') {
                Keuangan::create([
                    'id_pembayaran'   => $pembayaran->id,
                    'jumlah'          => $kompensasi->jumlah,
                    'keterangan'      => $kompensasi->keterangan,
                    'jenis_transaksi' => 'pengeluaran',
                    'waktu_transaksi' => now(),
                ]);
            }
        }

        return redirect()->route('pembayaran.index')->with('success', 'Pembayaran & Keuangan berhasil dicatat!');
    }

    private function tentukanJenisPemasukan($status)
    {
        if (str_contains($status, 'karcis hilang')) {
            return 'tiket_hilang';
        } elseif (str_contains($status, 'merusak')) {
            return 'denda_kerusakan';
        }

        return 'biaya_parkir';
    }

    public function autocompletePetugas(Request $request)
    {
        $term  = strtolower($request->term);
        $names = User::whereRaw('LOWER(name) LIKE ?', ["%{$term}%"])
            ->pluck('name');

        return response()->json($names);
    }

    public function exportPdf(Request $request)
    {
        $query = Pembayaran::with(['kendaraanMasuk.dataKendaraan', 'kendaraanKeluar', 'kompensasi', 'petugas']);

        if ($request->filled('petugas')) {
            $nama = strtolower($request->petugas);
            $query->whereHas('petugas', function ($q) use ($nama) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$nama}%"]);
            });
        }

        if ($request->filled('jenis_pembayaran')) {
            $query->where('jenis_pembayaran', $request->jenis_pembayaran);
        }

        $data = $query->latest()->get();

        $pdf = PDF::loadView('backend.pembayaran.pdf', compact('data'));
        return $pdf->download('data_pembayaran.pdf');
    }

    public function exportExcel(Request $request)
    {
        $query = Pembayaran::with(['kendaraanMasuk.dataKendaraan', 'kendaraanKeluar', 'kompensasi', 'petugas']);

        if ($request->filled('petugas')) {
            $nama = strtolower($request->petugas);
            $query->whereHas('petugas', function ($q) use ($nama) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$nama}%"]);
            });
        }

        if ($request->filled('jenis_pembayaran')) {
            $query->where('jenis_pembayaran', $request->jenis_pembayaran);
        }

        $data = $query->latest()->get();

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        // Judul kolom
        $header = ['No', 'Tanggal Bayar', 'Plat Nomor', 'Metode', 'Jumlah', 'Petugas'];
        $col    = 'A';
        foreach ($header as $title) {
            $sheet->setCellValue($col . '1', $title);
            $sheet->getStyle($col . '1')->getFont()->setBold(true); // Bold header
            $sheet->getColumnDimension($col)->setAutoSize(true);    // Auto width
            $col++;
        }

        // Data
        $row = 2;
        foreach ($data as $index => $item) {
            $sheet->setCellValue("A{$row}", $index + 1);
            $sheet->setCellValue("B{$row}", Carbon::parse($item->created_at)->format('d-m-Y H:i'));
            $sheet->setCellValue("C{$row}", $item->kendaraanMasuk->dataKendaraan->no_polisi ?? '-');
            $sheet->setCellValue("D{$row}", ucfirst($item->jenis_pembayaran ?? '-'));
            $sheet->setCellValue("E{$row}", 'Rp ' . number_format($item->total, 0, ',', '.'));
            $sheet->setCellValue("F{$row}", $item->petugas->name ?? '-');
            $row++;
        }

        // Border all cells (from A1 to F{lastRow})
        $lastRow    = $row - 1;
        $styleArray = [
            'borders'   => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['argb' => '000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
        ];
        $sheet->getStyle("A1:F{$lastRow}")->applyFromArray($styleArray);

        // Buat semua tinggi row nya lebih lega dikit (biar ga gepeng)
        for ($i = 1; $i <= $lastRow; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(22);
        }

        $writer   = new Xlsx($spreadsheet);
        $filename = 'data_pembayaran.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($tempFile);

        return Response::download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    public function dashboardPetugas()
    {
        $id_petugas = Auth::id();

        $grafikPetugas = Pembayaran::select(
            DB::raw('DATE(created_at) as tanggal'),
            DB::raw('SUM(total) as total')
        )
            ->where('id_petugas', $id_petugas)
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        return view('frontend.index', compact('grafikPetugas'));

    }

}
