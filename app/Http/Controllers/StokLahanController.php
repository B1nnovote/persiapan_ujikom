<?php
namespace App\Http\Controllers;
use Auth;
use App\Models\StokLahan;
use Illuminate\Http\Request;

class StokLahanController extends Controller
{
    // Tampilkan semua stok
    public function index()
    {
        $stok = StokLahan::all();
        return view('backend.stok.index', compact('stok'));
    }

    // Tampilkan form tambah stok
    public function create()
    {
        if (Auth::user()->isAdmin != 1) {
            abort(403, 'akses dibatasi');
        }
        return view('backend.stok.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->isAdmin != 1) {
            abort(403, 'akses dibatasi');
        }

        $request->validate([
            'jenis_kendaraan' => 'required|in:mobil,motor|unique:stok_lahans,jenis_kendaraan',
            'total_slot'      => 'required|integer|min:1',
        ]);

        StokLahan::create([
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'total_slot'      => $request->total_slot,
            'terpakai'        => 0,
        ]);

        return redirect()->route('stok.index')->with('success', 'Stok berhasil ditambahkan!');
    }

    // Edit stok
    public function edit($id)
    {
        if (Auth::user()->isAdmin != 1) {
            abort(403, 'akses dibatasi');
        }
        $stok = StokLahan::findOrFail($id);
        return view('backend.stok.edit', compact('stok'));
    }

    // Update stok
    public function update(Request $request, $id)
    {
        if (Auth::user()->isAdmin != 1) {
            abort(403, 'akses dibatasi');
        }

        $request->validate([
            'total_slot' => 'required|integer|min:1',
        ]);

        $stok = StokLahan::findOrFail($id);
        $stok->update([
            'total_slot' => $request->total_slot,
        ]);

        return redirect()->route('stok.index')->with('success', 'Stok berhasil diupdate!');
    }
    public function show($id)
    {
        $stok = StokLahan::findOrFail($id);
        return view('backend.stok.show', compact('stok'));
    }

}
