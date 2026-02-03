<?php
namespace App\Http\Controllers;

use App\Models\KendaraanMasuk;
use App\Models\Kompensasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KompensasiController extends Controller
{

    public function index(Request $request)
    {
        $kompensasi = Kompensasi::with('kendaraanMasuk.dataKendaraan')
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->nopol, function ($query) use ($request) {
                $query->whereHas('kendaraanMasuk.dataKendaraan', function ($q) use ($request) {
                    $q->where('no_polisi', 'like', '%' . $request->nopol . '%');
                });
            })
            ->latest()->get();

        return view('backend.kompensasi.index', compact('kompensasi'));
    }

    public function exportPdf(Request $request)
    {
        $kompensasi = Kompensasi::with('kendaraanMasuk.dataKendaraan')
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->nopol, function ($query) use ($request) {
                $query->whereHas('kendaraanMasuk.dataKendaraan', function ($q) use ($request) {
                    $q->where('no_polisi', 'like', '%' . $request->nopol . '%');
                });
            })
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('backend.kompensasi.pdf', compact('kompensasi'))->setPaper('A4', 'landscape');
        return $pdf->download('kompensasi_filtered.pdf');
    }

    public function exportExcel(Request $request)
    {
        $kompensasi = Kompensasi::with('kendaraanMasuk.dataKendaraan')
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->nopol, function ($query) use ($request) {
                $query->whereHas('kendaraanMasuk.dataKendaraan', function ($q) use ($request) {
                    $q->where('no_polisi', 'like', '%' . $request->nopol . '%');
                });
            })
            ->get();

        $filename = "kompensasi_filtered_" . date('Ymd_His') . ".xls";

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        echo "<table border='1'>";
        echo "<tr>
            <th>ID</th>
            <th>Nama Pemilik</th>
            <th>No Polisi</th>
            <th>Jumlah</th>
            <th>Status</th>
            <th>Diajukan</th>
            <th>Diproses</th>
        </tr>";

        foreach ($kompensasi as $item) {
            echo "<tr>
                <td>{$item->id}</td>
                <td>{$item->nama_pemilik}</td>
                <td>" . ($item->kendaraanMasuk->dataKendaraan->no_polisi ?? '-') . "</td>
                <td>Rp " . number_format($item->jumlah, 0, ',', '.') . "</td>
                <td>{$item->status}</td>
                <td>{$item->diajukan_pada}</td>
                <td>" . ($item->diproses_pada ?? '-') . "</td>
            </tr>";
        }

        echo "</table>";
        exit;
    }

    public function autocompleteNopol(Request $request)
    {
        $term = $request->get('term');

        $results = KendaraanMasuk::with('dataKendaraan')
            ->whereHas('dataKendaraan', function ($q) use ($term) {
                $q->where('no_polisi', 'like', '%' . $term . '%');
            })
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'label' => $item->dataKendaraan->no_polisi ?? 'N/A',
                    'value' => $item->dataKendaraan->no_polisi ?? 'N/A',
                ];
            });

        return response()->json($results);
    }

    public function create($idKendaraanMasuk)
    {
        $kendaraan = KendaraanMasuk::with('dataKendaraan')->findOrFail($idKendaraanMasuk);
        return view('backend.kompensasi.create', compact('kendaraan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kendaraan_masuk' => 'required|exists:kendaraan_masuks,id',
            'jumlah'             => 'required|integer|min:1000',
            'bukti_foto'         => 'nullable|image|max:2048',
            'keterangan'         => 'nullable|string|max:255',
        ]);

        $foto = null;
        if ($request->hasFile('bukti_foto')) {
            $foto = $request->file('bukti_foto')->store('kompensasi_foto', 'public');
        }

        Kompensasi::create([
            'id_kendaraan_masuk' => $request->id_kendaraan_masuk,
            'jumlah'             => $request->jumlah,
            'bukti_foto'         => $foto,
            'keterangan'         => $request->keterangan,
            // 'nama_pemilik'       => Auth::user()->name ?? 'Tanpa Nama',
            // 'nama_pemilik'       => $kendaraanMasuk->dataKendaraan->pemilik ?? 'Tanpa Nama',
            'nama_pemilik'       => $request->nama_pemilik ?? 'Tamu',
            'diajukan_pada'      => now(),
            'status'             => 'pending',
        ]);

        $user = Auth::user();

        if ($user->isAdmin === 1) {
            return redirect()->route('kompensasi.index')
                ->with('success', 'Kompensasi berhasil diajukan!');
        }

        return redirect()->route('frontend.index')
            ->with('success', 'Kompensasi berhasil diajukan, menunggu keputusan admin');
    }

    //baru1

    public function approval($id)
    {
        $kompensasi = Kompensasi::with('kendaraanMasuk.dataKendaraan')
            ->findOrFail($id);

        return view('backend.kompensasi.approval', compact('kompensasi'));
    }

    // public function approve($id)
    // {
    //     $kompensasi = Kompensasi::with('pembayaran')->findOrFail($id);

    //     // update status kompensasi
    //     $kompensasi->update([
    //         'status'        => 'disetujui',
    //         'diproses_pada' => now(),
    //     ]);

    //     // cari kendaraan keluar yang berkaitan
    //     $keluar = KendaraanKeluar::where('id_kendaraan_masuk', $kompensasi->id_kendaraan_masuk)->first();

    //     if (! $keluar) {
    //         return back()->with('error', 'Kendaraan keluar tidak ditemukan.');
    //     }

    //     // buat pembayaran kompensasi (hanya jika belum ada)
    //     $pembayaran = $kompensasi->pembayaran;

    //     if (! $pembayaran) {
    //         $pembayaran = Pembayaran::create([
    //             'id_kendaraan_masuk'  => $kompensasi->id_kendaraan_masuk,
    //             'id_kendaraan_keluar' => $keluar->id,
    //             'id_kompensasi'       => $kompensasi->id,
    //             'total'               => $kompensasi->jumlah,
    //             'pembayaran'          => 'kompensasi',
    //         ]);
    //     }

    //     // catat keuangan
    //     Keuangan::create([
    //         'id_pembayaran'   => $pembayaran->id,
    //         'jumlah'          => $kompensasi->jumlah,
    //         'jenis_transaksi' => 'pengeluaran',
    //         'jenis_pemasukan' => 'kompensasi_keluar',
    //         'keterangan'      => 'kompensasi_keluar',
    //         'waktu_transaksi' => now(),
    //     ]);

    //     return redirect()->back()->with('success', 'Kompensasi disetujui dan dicatat keuangan.');
    // }

    public function approve(Request $request, $id)
    {
        $request->validate([
            'jumlah'        => 'required|integer|min:1000',
            'catatan_admin' => 'nullable|string|max:255',
        ]);

        $kompensasi = Kompensasi::with('pembayaran')->findOrFail($id);

        $kompensasi->update([
            'jumlah'        => $request->jumlah,
            'status'        => 'disetujui',
            'diproses_pada' => now(),
            'catatan_admin' => $request->catatan_admin,
        ]);

         return redirect()->route('kompensasi.index')
            ->with('info', 'Kompensasi disetujui.');
    }

    // function reject($id)
    // {
    //     $kompensasi = Kompensasi::findOrFail($id);
    //     $kompensasi->update([
    //         'status'        => 'ditolak',
    //         'diproses_pada' => now(),
    //     ]);

    //     return redirect()->back()->with('info', 'Kompensasi ditolak.');
    // }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan_admin' => 'required|string|max:255',
        ]);

        $kompensasi = Kompensasi::findOrFail($id);
        $kompensasi->update([
            'status'        => 'ditolak',
            'diproses_pada' => now(),
            'catatan_admin' => $request->catatan_admin,
        ]);

        return redirect()->route('kompensasi.index')
            ->with('info', 'Kompensasi ditolak.');
    }

    public function edit($id)
    {
        $kompensasi = Kompensasi::with('kendaraanMasuk.dataKendaraan')->findOrFail($id);
        return view('backend.kompensasi.edit', compact('kompensasi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jumlah'     => 'required|integer|min:1000',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $kompensasi = Kompensasi::findOrFail($id);
        $kompensasi->update([
            'jumlah'     => $request->jumlah,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('kompensasi.index')->with('success', 'Data kompensasi berhasil diperbarui!');
    }

}
