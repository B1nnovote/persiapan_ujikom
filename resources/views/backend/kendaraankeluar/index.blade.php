<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kendaraan Keluar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .badge-status {
            font-size: 0.75rem;
            padding: 0.4em 0.6em;
            border-radius: 0.375rem;
            font-weight: 500;
        }
    </style>
</head>

<body>
    @include('layouts.part.sidebar')

    <section id="content">
        @include('layouts.part.navbar')

        <div class="container mt-5 pt-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-semibold mb-3 ms-2" style="font-size:30px;">Data Parkir Keluar</h4>
                <div class="d-flex flex-wrap gap-2 me-2">
                    <a href="{{ route('kendaraankeluar.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus"></i> Tambah
                    </a>
                    <a href="{{ route('kendaraankeluar.export.pdf', request()->query()) }}" class="btn btn-danger">
                        <i class="bx bxs-file-pdf"></i> Export PDF
                    </a>
                    <a href="{{ route('kendaraankeluar.export.excel', request()->query()) }}" class="btn btn-success">
                        <i class="bx bxs-file-export"></i> Export Excel
                    </a>
                </div>
            </div>

            <form method="GET" action="{{ route('kendaraankeluar.index') }}" class="d-flex flex-wrap gap-2 mb-4">
                <select name="jenis_kendaraan" class="form-select" style="max-width: 200px;">
                    <option value="">Semua</option>
                    <option value="mobil" {{ request('jenis_kendaraan') == 'mobil' ? 'selected' : '' }}>Mobil</option>
                    <option value="motor" {{ request('jenis_kendaraan') == 'motor' ? 'selected' : '' }}>Motor</option>
                </select>

                <select name="status_keluar" class="form-select" style="max-width: 200px;">
                    <option value="">Semua</option>
                    <option value="baik" {{ request('status_keluar') == 'baik' ? 'selected' : '' }}>Baik</option>
                    <option value="karcis hilang" {{ request('status_keluar') == 'karcis hilang' ? 'selected' : '' }}>
                        Karcis Hilang</option>
                    <option value="kerusakan" {{ request('status_keluar') == 'kerusakan' ? 'selected' : '' }}>Kerusakan
                    </option>
                    <option value="kehilangan" {{ request('status_keluar') == 'kehilangan' ? 'selected' : '' }}>
                        Kehilangan</option>
                    <option value="merusak" {{ request('status_keluar') == 'merusak' ? 'selected' : '' }}>Merusak
                    </option>
                </select>

                <input type="date" name="tanggal_keluar" class="form-control" style="max-width: 200px;"
                    value="{{ request('tanggal_keluar') }}">

                <button type="submit" class="btn btn-dark"><i class="bx bx-filter"></i> Filter</button>
                <a href="{{ route('kendaraankeluar.index') }}" class="btn btn-outline-secondary">Reset</a>
            </form>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle text-center mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Waktu Keluar</th>
                                <th>Status Kondisi</th>
                                <th>Plat Nomor</th>
                                <th>Jenis</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $row)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($row->waktu_keluar)->format('d-m-Y H:i') }}</td>
                                    <td>
                                        <span
                                            class="badge badge-status {{ $row->status_kondisi === 'baik' ? 'bg-success' : 'bg-danger' }}">
                                            {{ ucfirst($row->status_kondisi) }}
                                        </span>
                                    </td>
                                    <td>{{ $row->kendaraanMasuk->dataKendaraan->no_polisi ?? '-' }}</td>
                                    <td>{{ ucfirst($row->kendaraanMasuk->dataKendaraan->jenis_kendaraan ?? '-') }}</td>
                                    <td>
                                        <form id="form-delete-{{ $row->id }}"
                                            action="{{ route('kendaraankeluar.destroy', $row->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="confirmDelete({{ $row->id }})">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-muted text-center">Belum ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Yakin mau hapus?',
                text: "Data kendaraan akan terhapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#696cff',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-delete-' + id).submit();
                }
            });
        }

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 2000,
                showConfirmButton: false
            });
        @endif
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
