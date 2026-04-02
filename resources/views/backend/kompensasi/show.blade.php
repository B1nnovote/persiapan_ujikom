<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Detail Kompensasi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>

@include('layouts.part.sidebar')

<section id="content">
    @include('layouts.part.navbar')

    <main class="p-4">

        <h2 class="mb-4">Detail Kompensasi</h2>

        <table class="table table-bordered">
            <tr>
                <th>Status Pemilik</th>
                <td>{{ $kompensasi->nama_pemilik }}</td>
            </tr>
            <tr>
                <th>No Polisi</th>
                <td>{{ $kompensasi->kendaraanMasuk->dataKendaraan->no_polisi ?? '-' }}</td>
            </tr>
            <tr>
                <th>Jenis Kendaraan</th>
                <td>{{ $kompensasi->kendaraanMasuk->dataKendaraan->jenis_kendaraan ?? '-' }}</td>
            </tr>
            <tr>
                <th>Jumlah Kompensasi</th>
                <td>Rp {{ number_format($kompensasi->jumlah, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    @if ($kompensasi->status == 'pending')
                        <span class="badge bg-warning">Menunggu</span>
                    @elseif ($kompensasi->status == 'disetujui')
                        <span class="badge bg-success">Disetujui</span>
                    @else
                        <span class="badge bg-danger">Ditolak</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Catatan Admin</th>
                <td>{{ $kompensasi->catatan_admin ?? '-' }}</td>
            </tr>
            <tr>
                <th>Diajukan Pada</th>
                <td>{{ $kompensasi->diajukan_pada }}</td>
            </tr>
            <tr>
                <th>Diproses Pada</th>
                <td>{{ $kompensasi->diproses_pada ?? '-' }}</td>
            </tr>
        </table>

        <a href="{{ route('kompensasi.index') }}" class="btn btn-secondary mt-3">
            ⬅ Kembali
        </a>

    </main>
</section>

</body>
</html>
