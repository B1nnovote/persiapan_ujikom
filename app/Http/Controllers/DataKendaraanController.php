<?php
namespace App\Http\Controllers;

use App\Models\DataKendaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use PDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DataKendaraanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = DataKendaraan::query();

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereRaw("LOWER(CONCAT(no_polisi, ' - ', jenis_kendaraan, ' - ', status_pemilik)) LIKE ?", ["%{$search}%"])
                    ->orWhereRaw('LOWER(no_polisi) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(jenis_kendaraan) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(status_pemilik) LIKE ?', ["%{$search}%"]);
            });
        }

        $dataKendaraan = $query->latest()->paginate(10);

        return view('backend.datakendaraan.index', compact('dataKendaraan'));
    }

    public function create()
    {
        return view('backend.datakendaraan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_polisi'       => 'required|string|max:20|unique:data_kendaraans,no_polisi',
            'jenis_kendaraan' => 'required|string',
            'pemilik'         => 'required|string',
            'status_pemilik'  => 'required|string',
        ], [
            'no_polisi.unique' => 'Nomor polisi ini sudah terdaftar.',
        ]);

        DataKendaraan::create($request->only(['no_polisi', 'jenis_kendaraan', 'pemilik', 'status_pemilik']));

        return redirect()->route('datakendaraan.index')->with('success', 'Data berhasil ditambahkan!');
    }

    public function show($id)
    {
        $data = DataKendaraan::findOrFail($id);
        return view('frontend.datakendaraan.show', compact('data'));
    }

    public function edit($id)
    {
        $data = DataKendaraan::findOrFail($id);
        return view('backend.datakendaraan.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'no_polisi'       => 'required|string|max:20|unique:data_kendaraans,no_polisi,' . $id,
            'jenis_kendaraan' => 'required|string',
            'pemilik'         => 'required|string',
            'status_pemilik'  => 'required|string',
        ]);

        $dataKendaraan = DataKendaraan::findOrFail($id);
        $dataKendaraan->update($request->only(['no_polisi', 'jenis_kendaraan', 'pemilik', 'status_pemilik']));

        return redirect()->route('datakendaraan.index')->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $data = DataKendaraan::findOrFail($id);
        $data->delete();

        return redirect()->route('datakendaraan.index')->with('success', 'Data berhasil dihapus!');
    }

    public function autocomplete(Request $request)
    {
        $term = $request->get('term');

        $results = DataKendaraan::where('no_polisi', 'like', "%$term%")
            ->orWhere('jenis_kendaraan', 'like', "%$term%")
            ->orWhere('status_pemilik', 'like', "%$term%")
            ->limit(10)
            ->get();

        $formatted = $results->map(function ($item) {
            return [
                'label' => "{$item->no_polisi} - {$item->jenis_kendaraan} - {$item->status_pemilik}",
                'value' => "{$item->no_polisi} - {$item->jenis_kendaraan} - {$item->status_pemilik}",
            ];
        });

        return response()->json($formatted);
    }

    public function exportPdf(Request $request)
    {
        $query = DataKendaraan::query();

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereRaw("LOWER(CONCAT(no_polisi, ' - ', jenis_kendaraan, ' - ', status_pemilik)) LIKE ?", ["%{$search}%"])
                    ->orWhereRaw('LOWER(no_polisi) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(jenis_kendaraan) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(status_pemilik) LIKE ?', ["%{$search}%"]);
            });
        }

        $dataKendaraan = $query->get();

        $pdf = PDF::loadView('backend.datakendaraan.pdf', compact('dataKendaraan'));
        return $pdf->download('data_kendaraan.pdf');
    }

    public function exportExcel(Request $request)
    {
        $search = strtolower($request->search);

        $query = DataKendaraan::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw("LOWER(CONCAT(no_polisi, ' - ', jenis_kendaraan, ' - ', status_pemilik)) LIKE ?", ["%{$search}%"])
                    ->orWhereRaw('LOWER(no_polisi) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(jenis_kendaraan) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(status_pemilik) LIKE ?', ["%{$search}%"]);
            });
        }

        $data = $query->get();

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        // Judul Kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Plat Nomor');
        $sheet->setCellValue('C1', 'Jenis Kendaraan');
        $sheet->setCellValue('D1', 'Pemilik');
        $sheet->setCellValue('E1', 'Status Pemilik');

        $row = 2;
        foreach ($data as $index => $item) {
            $sheet->setCellValue("A{$row}", $index + 1);
            $sheet->setCellValue("B{$row}", $item->no_polisi);
            $sheet->setCellValue("C{$row}", ucfirst($item->jenis_kendaraan));
            $sheet->setCellValue("D{$row}", $item->pemilik);
            $sheet->setCellValue("E{$row}", ucfirst($item->status_pemilik));
            $row++;
        }

        $writer   = new Xlsx($spreadsheet);
        $filename = 'data_kendaraan.xlsx';

        $temp_file = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($temp_file);

        return response()->download($temp_file, $filename)->deleteFileAfterSend(true);
    }
}
