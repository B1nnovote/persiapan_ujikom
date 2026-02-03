<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kendaraan Masuk</title>
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
        <center>
            <div class="container mt-5 pt-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="fw-semibold mb-3  ms-5" style="font-size:30px;">Data Parkir Masuk</h4>
                    <div class="d-flex flex-wrap gap-2 me-5 mb-3">
                        <a href="{{ route('kendaraanmasuk.create') }}" class="btn btn-primary">
                            <i class="bx bx-plus"></i> Tambah
                        </a>

                        <a href="{{ route('kendaraanmasuk.export.pdf', request()->query()) }}" class="btn btn-danger">
                            <i class="bx bxs-file-pdf"></i> Export PDF
                        </a>

                        <a href="{{ route('kendaraanmasuk.export.excel', request()->query()) }}"
                            class="btn btn-success">
                            <i class="bx bxs-file-export"></i> Export Excel
                        </a>
                    </div>

                </div>
                <form method="GET" action="{{ route('kendaraanmasuk.index') }}" class="d-flex gap-2 mb-4 ms-5">
                    <select name="status" class="form-select" style="max-width: 200px;">
                        <option value=""> Semua </option>
                        <option value="sedang parkir" {{ request('status') == 'sedang parkir' ? 'selected' : '' }}>
                            Sedang Parkir</option>
                        <option value="sudah keluar" {{ request('status') == 'sudah keluar' ? 'selected' : '' }}>
                            Sudah
                            Keluar</option>
                    </select>

                    <select name="jenis_kendaraan" class="form-select" style="max-width: 200px;">
                        <option value=""> Semua </option>
                        <option value="motor" {{ request('jenis_kendaraan') == 'motor' ? 'selected' : '' }}>Motor
                        </option>
                        <option value="mobil" {{ request('jenis_kendaraan') == 'mobil' ? 'selected' : '' }}>Mobil
                        </option>
                    </select>

                    <button type="submit" class="btn btn-dark"><i class="bx bx-filter"></i> Filter</button>
                    <a href="{{ route('kendaraanmasuk.index') }}" class="btn btn-outline-secondary">Reset</a>
                </form>


                <div class="card shadow-sm border-0 col-11">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle text-center mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Waktu Masuk</th>
                                    <th>Status</th>
                                    <th>Plat Nomor</th>
                                    <th>Jenis</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $row)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($row->waktu_masuk)->format('d-m-Y H:i') }}</td>
                                        <td>
                                            <span
                                                class="badge badge-status {{ $row->status_parkir === 'sedang parkir' ? 'bg-warning text-dark' : 'bg-success' }}">
                                                {{ ucfirst($row->status_parkir) }}
                                            </span>
                                        </td>
                                        <td>{{ $row->dataKendaraan->no_polisi ?? '-' }}</td>
                                        <td>{{ ucfirst($row->dataKendaraan->jenis_kendaraan ?? '-') }}</td>
                                        <td>
                                            <div class="btn-group gap-2">
                                                <form id="form-delete-{{ $row->id }}"
                                                    action="{{ route('kendaraanmasuk.destroy', $row->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        onclick="confirmDelete({{ $row->id }})">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </form>

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Belum ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </center>
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
