<?php
namespace App\Http\Controllers;

use App\Models\Keuangan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class KeuanganController extends Controller
{
    /**
     * Menampilkan semua data keuangan dengan filter jenis transaksi
     */
    public function index(Request $request)
    {
        $data = Keuangan::with('pembayaran.kendaraanMasuk.dataKendaraan')
            ->when($request->jenis_transaksi, function ($query) use ($request) {
                $query->where('jenis_transaksi', $request->jenis_transaksi);
            })
            ->latest()
            ->get();

        return view('backend.keuangan.index', compact('data'));
    }

    /**
     * Menampilkan detail 1 transaksi keuangan
     */
    public function show($id)
    {
        $keuangan = Keuangan::with('pembayaran.kendaraanKeluar', 'pembayaran.kendaraanMasuk')->findOrFail($id);
        return view('backend.keuangan.show', compact('keuangan'));
    }

    /**
     * Menyimpan data keuangan secara manual (opsional)
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_pembayaran'   => 'required|exists:pembayarans,id',
            'jumlah'          => 'required|numeric|min:0',
            'keterangan'      => 'required|in:biaya_parkir,kompensasi,tiket_hilang,denda,lainnya',
            'jenis_transaksi' => 'required|in:pemasukan,pengeluaran',
        ]);

        Keuangan::create([
            'id_pembayaran'   => $request->id_pembayaran,
            'jumlah'          => $request->jumlah,
            'keterangan'      => $request->keterangan,
            'jenis_transaksi' => $request->jenis_transaksi,
            'waktu_transaksi' => now(),
        ]);

        Alert::success('Berhasil!', 'Data keuangan berhasil disimpan!');
        return redirect()->route('keuangan.index');
    }

    /**
     * Menghapus data keuangan
     */
    public function destroy($id)
    {
        $data = Keuangan::findOrFail($id);
        $data->delete();
        return redirect()->route('keuangan.index')->with('success', 'Data berhasil dihapus!');

    }

    /**
     * Export PDF
     */
    public function exportPdf(Request $request)
    {
        $data = Keuangan::with('pembayaran.kendaraanMasuk.dataKendaraan')
            ->when($request->jenis_transaksi, function ($query) use ($request) {
                $query->where('jenis_transaksi', $request->jenis_transaksi);
            })
            ->get();

        $pdf = Pdf::loadView('backend.keuangan.pdf', compact('data'))
            ->setPaper('A4', 'landscape');

        return $pdf->download('keuangan_filtered.pdf');
    }

    /**
     * Export Excel manual
     */
    public function exportExcel(Request $request)
    {
        $data = Keuangan::with('pembayaran.kendaraanMasuk.dataKendaraan')
            ->when($request->jenis_transaksi, function ($query) use ($request) {
                $query->where('jenis_transaksi', $request->jenis_transaksi);
            })
            ->get();

        $filename = "keuangan_filtered_" . date('Ymd_His') . ".xls";

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        echo "<table border='1'>";
        echo "<tr>
                <th>ID</th>
                <th>Jenis Transaksi</th>
                <th>Jumlah</th>
                <th>Waktu</th>
                <th>Keterangan</th>
                <th>No Polisi</th>
              </tr>";

        foreach ($data as $item) {
            echo "<tr>
                    <td>{$item->id}</td>
                    <td>{$item->jenis_transaksi}</td>
                    <td>Rp " . number_format($item->jumlah, 0, ',', '.') . "</td>
                    <td>{$item->waktu_transaksi}</td>
                    <td>{$item->keterangan}</td>
                    <td>" . ($item->pembayaran->kendaraanMasuk->dataKendaraan->no_polisi ?? '-') . "</td>
                  </tr>";
        }

        echo "</table>";
        exit;
    }
}
