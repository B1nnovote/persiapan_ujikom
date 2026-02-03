<?php
namespace App\Http\Controllers;

use App\Models\KendaraanKeluar;
use App\Models\KendaraanMasuk;
use App\Models\StokLahan;
use App\Models\Tarif;
use Illuminate\Http\Request;
use PDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Response;

class ParkirKeluarController extends Controller
{
    public function index(Request $request)
    {
        $query = KendaraanKeluar::with('kendaraanMasuk.dataKendaraan');

        if ($request->filled('status_keluar')) {
            $query->where('status_kondisi', 'like', "%{$request->status_keluar}%");
        }

        if ($request->filled('jenis_kendaraan')) {
            $query->whereHas('kendaraanMasuk.dataKendaraan', function ($q) use ($request) {
                $q->where('jenis_kendaraan', $request->jenis_kendaraan);
            });
        }

        if ($request->filled('tanggal_keluar')) {
            $query->whereDate('waktu_keluar', $request->tanggal_keluar);
        }

        $data = $query->latest()->paginate(10);

        return view('backend.kendaraankeluar.index', compact('data'));
    }

    public function show($id)
    {
        $data = KendaraanKeluar::with('kendaraanMasuk.dataKendaraan')->findOrFail($id);
        return view('backend.kendaraankeluar.show', compact('data'));
    }

    public function create()
    {
        $parkirAktif = KendaraanMasuk::where('status_parkir', 'sedang parkir')->get();
        return view('backend.kendaraankeluar.create', compact('parkirAktif'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kendaraan_masuk' => 'required|exists:kendaraan_masuks,id',
            'waktu_keluar'       => 'required|date',
            'status_kondisi'     => 'required|array',
            'status_kondisi.*'   => 'in:baik,karcis hilang,kerusakan,kehilangan,merusak',
        ]);

        $kendaraanMasuk = KendaraanMasuk::findOrFail($request->id_kendaraan_masuk);

        if (strtotime($request->waktu_keluar) < strtotime($kendaraanMasuk->waktu_masuk)) {
            return back()->with('error', 'Waktu keluar tidak boleh lebih awal dari waktu masuk.')->withInput();
        }

        $kendaraanMasuk->update(['status_parkir' => 'sudah keluar']);

        $kendaraan = $kendaraanMasuk->dataKendaraan->jenis_kendaraan;
        $stok = StokLahan::where('jenis_kendaraan', $kendaraan)->first();
        if ($stok) {
            $stok->terpakai = max(0, $stok->terpakai - 1);
            $stok->save();
        }

        $statusGabung = implode(', ', $request->status_kondisi);
        $keluar = KendaraanKeluar::create([
            'id_kendaraan_masuk' => $kendaraanMasuk->id,
            'waktu_keluar'       => $request->waktu_keluar,
            'status_kondisi'     => $statusGabung,
        ]);

        $statusLower = strtolower($statusGabung);

        if (str_contains($statusLower, 'kerusakan') || str_contains($statusLower, 'kehilangan')) {
            return redirect()->route('kompensasi.create', ['idKendaraanMasuk' => $keluar->id_kendaraan_masuk])
                ->with('success', 'Kendaraan keluar dicatat. Mohon isi form kompensasi.');
        }

        return redirect()->route('pembayaran.create', $keluar->id)
            ->with('success', 'Kendaraan keluar berhasil dicatat.');
    }

    public function edit($id)
    {
        $data = KendaraanKeluar::with('kendaraanMasuk')->findOrFail($id);
        return view('backend.kendaraankeluar.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'waktu_keluar'     => 'required|date',
            'status_kondisi'   => 'required|array',
            'status_kondisi.*' => 'in:baik,karcis hilang,kerusakan,kehilangan,merusak',
        ]);

        $data = KendaraanKeluar::findOrFail($id);
        $data->update([
            'waktu_keluar'   => $request->waktu_keluar,
            'status_kondisi' => implode(', ', $request->status_kondisi),
        ]);

        return redirect()->route('kendaraankeluar.index')
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function cekKondisi($id)
    {
        $keluar = KendaraanKeluar::findOrFail($id);
        $status = strtolower($keluar->status_kondisi);

        if (str_contains($status, 'baik') || str_contains($status, 'karcis hilang')) {
            return redirect()->route('pembayaran.create', $keluar->id);
        }

        if (str_contains($status, 'kerusakan') || str_contains($status, 'kehilangan')) {
            return redirect()->route('kompensasi.create', ['id_kendaraan_masuk' => $keluar->id_kendaraan_masuk]);
        }

        return redirect()->back()->with('error', 'Status kondisi tidak valid!');
    }

    public function destroy($id)
    {
        $data = KendaraanKeluar::findOrFail($id);
        $data->delete();

        return back()->with('success', 'Data berhasil dihapus.');
    }

    public function exportPdf(Request $request)
    {
        $data = $this->filterData($request)->get();

        $pdf = PDF::loadView('backend.kendaraankeluar.pdf', compact('data'));
        return $pdf->download('kendaraan_keluar.pdf');
    }

   public function exportExcel(Request $request)
{
    $data = $this->filterData($request)->get();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $headers = ['Plat Nomor', 'Jenis Kendaraan', 'Waktu Keluar', 'Status Kondisi'];
    $col = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue($col . '1', $header);
        $sheet->getStyle($col . '1')->getFont()->setBold(true);
        $sheet->getColumnDimension($col)->setAutoSize(false)->setWidth(25); // Set lebar manual biar rata
        $sheet->getStyle($col . '1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $col++;
    }

    $row = 2;
    foreach ($data as $item) {
        $sheet->setCellValue('A' . $row, $item->kendaraanMasuk->dataKendaraan->no_polisi ?? '-');
        $sheet->setCellValue('B' . $row, $item->kendaraanMasuk->dataKendaraan->jenis_kendaraan ?? '-');
        $sheet->setCellValue('C' . $row, \Carbon\Carbon::parse($item->waktu_keluar)->format('d-m-Y H:i'));
        $sheet->setCellValue('D' . $row, $item->status_kondisi);

        // Apply border tiap baris
        foreach (range('A', 'D') as $col) {
            $sheet->getStyle($col . $row)->getBorders()->getAllBorders()->setBorderStyle(
                \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
            );
            $sheet->getStyle($col . $row)->getAlignment()->setHorizontal(
                \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            );
        }

        $row++;
    }

    $writer = new Xlsx($spreadsheet);
    $filename = 'kendaraan_keluar.xlsx';
    $tempFile = tempnam(sys_get_temp_dir(), $filename);
    $writer->save($tempFile);

    return Response::download($tempFile, $filename)->deleteFileAfterSend(true);
}

    private function filterData(Request $request)
    {
        $query = KendaraanKeluar::with('kendaraanMasuk.dataKendaraan');

        if ($request->filled('status_keluar')) {
            $query->where('status_kondisi', 'like', "%{$request->status_keluar}%");
        }

        if ($request->filled('jenis_kendaraan')) {
            $query->whereHas('kendaraanMasuk.dataKendaraan', function ($q) use ($request) {
                $q->where('jenis_kendaraan', $request->jenis_kendaraan);
            });
        }

        if ($request->filled('tanggal_keluar')) {
            $query->whereDate('waktu_keluar', $request->tanggal_keluar);
        }

        return $query;
    }
}
