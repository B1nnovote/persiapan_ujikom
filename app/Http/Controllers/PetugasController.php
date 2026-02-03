<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use PDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PetugasController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('isAdmin', 0);

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"]);
        }

        $petugas = $query->latest()->paginate(10)->withQueryString();
        return view('backend.datapetugas.index', compact('petugas'));
    }

    public function create()
    {
        return view('backend.datapetugas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'foto'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $foto = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('petugas', 'public');
        }

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'foto'     => $foto,
            'isAdmin'  => 0,
        ]);

        Alert::success('Berhasil!', 'Petugas berhasil ditambahkan.');
        return redirect()->route('petugas.index');
    }

    public function edit($id)
    {
        $petugas = User::findOrFail($id);
        return view('backend.datapetugas.edit', compact('petugas'));
    }

    public function update(Request $request, $id)
    {
        $petugas = User::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $petugas->id,
            'password' => 'nullable|string|min:6|confirmed',
            'foto'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $petugas->name  = $request->name;
        $petugas->email = $request->email;

        if ($request->filled('password')) {
            $petugas->password = Hash::make($request->password);
        }

        if ($request->hasFile('foto')) {
            if ($petugas->foto) {
                Storage::disk('public')->delete($petugas->foto);
            }
            $petugas->foto = $request->file('foto')->store('petugas', 'public');
        }

        $petugas->save();

        Alert::success('Berhasil!', 'Data petugas berhasil diperbarui.');
        return redirect()->route('petugas.index');
    }

    public function destroy($id)
    {
        $petugas = User::findOrFail($id);

        if ($petugas->foto) {
            Storage::disk('public')->delete($petugas->foto);
        }

        $petugas->delete();

        Alert::success('Berhasil!', 'Petugas berhasil dihapus.');
        return redirect()->route('petugas.index');
    }

    public function autocomplete(Request $request)
    {
        $term = $request->get('term');

        $results = User::where('isAdmin', 0)
            ->where('name', 'like', "%$term%")
            ->limit(10)
            ->get();

        $formatted = $results->map(function ($item) {
            return [
                'label' => $item->name,
                'value' => $item->name,
            ];
        });

        return response()->json($formatted);
    }

    public function exportPdf(Request $request)
    {
        $query = User::where('isAdmin', 0);
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"]);
        }

        $petugas = $query->get();

        $pdf = PDF::loadView('backend.datapetugas.pdf', compact('petugas'))->setPaper('A4', 'portrait');
        return $pdf->download('data_petugas.pdf');
    }

    public function exportExcel(Request $request)
    {
        $query = User::where('isAdmin', 0);

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"]);
        }

        $petugas = $query->get();

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama');
        $sheet->setCellValue('C1', 'Email');

        $row = 2;
        foreach ($petugas as $index => $data) {
            $sheet->setCellValue("A{$row}", $index + 1);
            $sheet->setCellValue("B{$row}", $data->name);
            $sheet->setCellValue("C{$row}", $data->email);
            $row++;
        }

        $writer   = new Xlsx($spreadsheet);
        $filename = 'data_petugas.xlsx';

        $temp_file = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($temp_file);

        return response()->download($temp_file, $filename)->deleteFileAfterSend(true);
    }
}
