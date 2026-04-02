<?php
namespace App\Http\Controllers;

use App\Models\DataKendaraan;
use App\Models\KendaraanMasuk;
use App\Models\StokLahan;
use App\Models\Tarif;
use Illuminate\Http\Request;
use PDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ParkirMasukController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = KendaraanMasuk::with('dataKendaraan');

        // Filter status parkir
        if ($request->filled('status')) {
            $query->where('status_parkir', $request->status);
        }

        // Filter jenis kendaraan
        if ($request->filled('jenis_kendaraan')) {
            $query->whereHas('dataKendaraan', function ($q) use ($request) {
                $q->where('jenis_kendaraan', $request->jenis_kendaraan);
            });
        }

        $data = $query->latest()->paginate(10);

        return view('backend.kendaraanmasuk.index', compact('data'));
    }

    public function create()
    {
        return view('backend.kendaraanmasuk.create');
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'no_polisi'       => 'required|string|max:20',
    //         'jenis_kendaraan' => 'required|in:mobil,motor',
    //         'waktu_masuk'     => 'required|date',
    //         'status_parkir'   => 'required|in:sedang parkir',
    //     ]);

    //     $stok = StokLahan::where('jenis_kendaraan', $request->jenis_kendaraan)->first();

    //     if (! $stok || $stok->terpakai >= $stok->total_slot) {
    //         return redirect()->back()->with('error', 'Slot parkir untuk ' . $request->jenis_kendaraan . ' penuh.');
    //     }

    //     $dataKendaraan = DataKendaraan::firstOrCreate(
    //         ['no_polisi' => $request->no_polisi],
    //         [
    //             'jenis_kendaraan' => $request->jenis_kendaraan,
    //             'pemilik'         => null,
    //             'status_pemilik'  => 'tamu',
    //         ]
    //     );

    //     $sudahParkir = KendaraanMasuk::where('id_kendaraan', $dataKendaraan->id)
    //         ->where('status_parkir', 'sedang parkir')
    //         ->exists();

    //     if ($sudahParkir) {
    //         return redirect()->back()->with('error', 'Kendaraan ini sedang parkir.');
    //     }

    //     $kendaraanMasuk = KendaraanMasuk::create([
    //         'waktu_masuk'   => $request->waktu_masuk ?? now(),
    //         'status_parkir' => $request->status_parkir,
    //         'id_kendaraan'  => $dataKendaraan->id,
    //     ]);

    //     $stok->increment('terpakai');

    //     return redirect()->route('kendaraanmasuk.karcis.karcis', $kendaraanMasuk->id);
    // }

    public function store(Request $request)
    {
        $request->validate([
            'no_polisi'       => 'required|string|max:20',
            'jenis_kendaraan' => 'required|in:mobil,motor',
            'waktu_masuk'     => 'required|date',
            'status_parkir'   => 'required|in:sedang parkir',
        ]);

        $stok = StokLahan::where('jenis_kendaraan', $request->jenis_kendaraan)->first();

        if (! $stok || $stok->terpakai >= $stok->total_slot) {
            return redirect()->back()->with('error', 'Slot parkir untuk ' . $request->jenis_kendaraan . ' penuh.');
        }

        // ambil atau buat data kendaraan
        $dataKendaraan = DataKendaraan::firstOrCreate(
            ['no_polisi' => $request->no_polisi],
            [
                'jenis_kendaraan' => $request->jenis_kendaraan,
                'pemilik'         => null,
                'status_pemilik'  => 'tamu',
            ]
        );

        // cek kalau kendaraan masih parkir
        $sudahParkir = KendaraanMasuk::where('id_kendaraan', $dataKendaraan->id)
            ->where('status_parkir', 'sedang parkir')
            ->exists();

        if ($sudahParkir) {
            return redirect()->back()->with('error', 'Kendaraan ini sedang parkir.');
        }

        // generate kode tiket otomatis
        $kodeTiket = KendaraanMasuk::generateKodeTiket($request->jenis_kendaraan);

        // simpan kendaraan masuk
        $kendaraanMasuk = KendaraanMasuk::create([
            'waktu_masuk'     => $request->waktu_masuk ?? now(),
            'status_parkir'   => $request->status_parkir,
            'id_kendaraan'    => $dataKendaraan->id,
            'kode_tiket'      => $kodeTiket,
        ]);

        $stok->increment('terpakai');

        return redirect()->route('kendaraanmasuk.karcis.karcis', $kendaraanMasuk->id);
    }

    public function destroy($id)
    {
        KendaraanMasuk::findOrFail($id)->delete();
        return back()->with('success', 'Data berhasil dihapus.');
    }

    public function karcis($id)
    {
        $data = KendaraanMasuk::with('dataKendaraan')->findOrFail($id);

        $jenisKendaraan = strtolower(
            $data->dataKendaraan->jenis_kendaraan ?? 'motor'
        );

        $tarif = Tarif::where('jenis_kendaraan', $jenisKendaraan)
            ->where('jenis_tarif', 'biasa')
            ->first();

        // $kodeTiket = $tarif?->kode_tiket ?? '-';
        $kodeTiket = $data->kode_tiket ?? '-';

        return view('backend.kendaraanmasuk.karcis.karcis', [
            'data'       => $data,
            'kode_tiket' => $kodeTiket,
        ]);
    }

    public function exportPdf(Request $request)
    {
        $query = KendaraanMasuk::with('dataKendaraan');

        if ($request->filled('status')) {
            $query->where('status_parkir', $request->status);
        }

        if ($request->filled('jenis_kendaraan')) {
            $query->whereHas('dataKendaraan', function ($q) use ($request) {
                $q->where('jenis_kendaraan', $request->jenis_kendaraan);
            });
        }

        $data = $query->latest()->get();

        $pdf = PDF::loadView('backend.kendaraanmasuk.pdf', compact('data'))
            ->setPaper('A4', 'portrait');

        return $pdf->download('data_parkir_masuk.pdf');
    }

    public function exportExcel(Request $request)
    {
        $query = KendaraanMasuk::with('dataKendaraan');

        if ($request->filled('status')) {
            $query->where('status_parkir', $request->status);
        }

        if ($request->filled('jenis_kendaraan')) {
            $query->whereHas('dataKendaraan', function ($q) use ($request) {
                $q->where('jenis_kendaraan', $request->jenis_kendaraan);
            });
        }

        $data = $query->latest()->get();

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Waktu Masuk');
        $sheet->setCellValue('C1', 'Status Parkir');
        $sheet->setCellValue('D1', 'Plat Nomor');
        $sheet->setCellValue('E1', 'Jenis Kendaraan');

        $row = 2;
        foreach ($data as $index => $item) {
            $sheet->setCellValue("A{$row}", $index + 1);
            $sheet->setCellValue("B{$row}", \Carbon\Carbon::parse($item->waktu_masuk)->format('d-m-Y H:i'));
            $sheet->setCellValue("C{$row}", ucfirst($item->status_parkir));
            $sheet->setCellValue("D{$row}", $item->dataKendaraan->no_polisi ?? '-');
            $sheet->setCellValue("E{$row}", ucfirst($item->dataKendaraan->jenis_kendaraan ?? '-'));
            $row++;
        }

        $writer    = new Xlsx($spreadsheet);
        $filename  = 'data_parkir_masuk.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($temp_file);

        return response()->download($temp_file, $filename)->deleteFileAfterSend(true);
    }

}
