<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Keuangan</title>
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
                    <h4 class="fw-semibold mb-3 ms-5" style="font-size:30px;">Data Keuangan</h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('keuangan.export.pdf', request()->only('jenis_transaksi')) }}"
                            class="btn btn-danger">
                            <i class="bx bxs-file-pdf"></i> Export PDF
                        </a>
                        <a href="{{ route('keuangan.export.excel', request()->only('jenis_transaksi')) }}"
                            class="btn btn-success">
                            <i class="bx bxs-file-export"></i> Export Excel
                        </a>
                    </div>
                </div>

                <form method="GET" class="row g-2 align-items-end mb-4">
                    <div class="col-md-4">
                        <select name="jenis_transaksi" id="jenis_transaksi" class="form-select">
                            <option value="">Semua</option>
                            <option value="pemasukan" {{ request('jenis_transaksi') == 'pemasukan' ? 'selected' : '' }}>
                                Pemasukan</option>
                            <option value="pengeluaran"
                                {{ request('jenis_transaksi') == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-dark "><i class="bx bx-filter"></i> Filter</button>
                        <a href="{{ route('keuangan.index') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle text-center mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Jumlah</th>
                                <th>Transaksi</th>
                                <th>Jenis Transaksi</th>
                                <th>Plat Nomor</th>
                                <th>Metode</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $row)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ \Carbon\Carbon::parse($row->waktu_transaksi)->format('d-m-Y H:i') }}
                                    </td>
                                    <td>Rp {{ number_format($row->jumlah, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge badge-status bg-info text-light text-uppercase">
                                            {{ str_replace('_', ' ', $row->keterangan ?? '-') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span
                                            class="badge badge-status  {{ $row->jenis_transaksi === 'pemasukan' ? 'bg-success' : 'bg-danger' }} bg-info text-light text-uppercase">
                                            {{ str_replace('_', ' ', $row->jenis_transaksi ?? '-') }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ optional($row->pembayaran?->kendaraanMasuk?->dataKendaraan)->no_polisi ?? '-' }}
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $row->pembayaran->pembayaran == 'kompensasi' ? 'danger' : 'secondary' }}">
                                            {{ ucfirst($row->pembayaran->pembayaran ?? '-') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('keuangan.show', $row->id) }}" class="btn btn-sm"
                                                style="background-color:#696cff; color:white;">
                                                <i class="bx bx-show"></i>
                                            </a>
                                            <form action="{{ route('keuangan.destroy', $row->id) }}" method="POST"
                                                onsubmit="return confirmDelete(this);" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger"><i
                                                        class="bx bx-trash"></i></button>
                                            </form>
                                           
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-muted">Belum ada data keuangan.</td>
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
        $(function() {
            $("#search-petugas").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('petugas.autocomplete') }}",
                        data: {
                            term: request.term
                        },
                        success: function(data) {
                            response(data);
                        }
                    });
                },
                minLength: 2
            });
        });

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2000
            })
        @endif

        // SweetAlert Confirm Delete
        function confirmDelete(form) {
            event.preventDefault();
            Swal.fire({
                title: 'Yakin?',
                text: "Data kendaraan akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#696cff',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
            return false;
        }
    </script>
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
