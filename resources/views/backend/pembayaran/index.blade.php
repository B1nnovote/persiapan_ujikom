<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pembayaran</title>
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

        <div class="container mt-5 pt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-semibold mb-3" style="font-size:30px;">Data Pembayaran</h4>
                <div class="d-flex gap-2">
                    <a href="{{ route('pembayaran.export.pdf', request()->query()) }}" class="btn btn-danger">
                        <i class="bx bxs-file-pdf"></i> Export PDF
                    </a>

                    <a href="{{ route('pembayaran.export.excel', request()->query()) }}" class="btn btn-success">
                        <i class="bx bxs-file-export"></i> Export Excel
                    </a>
                </div>
            </div>

            <form method="GET" action="{{ route('pembayaran.index') }}" class="d-flex flex-wrap gap-2 mb-3">

                <input type="text" name="petugas" list="petugas" class="form-control"
                    placeholder="Cari nama petugas..." style="max-width: 200px;" value="{{ request('petugas') }}">

                <datalist id="petugas">
                    @foreach ($semuaPetugas as $user)
                        <option value="{{ $user->name }}">
                    @endforeach
                </datalist>

                {{-- <input type="text" name="petugas" class="form-control" placeholder="Cari nama petugas"
                    value="{{ request('petugas') }}" style="max-width: 200px;"> --}}

                <select name="pembayaran" class="form-select" style="max-width: 200px;">
                    <option value="">Semua Pembayaran</option>
                    <option value="tunai" {{ request('pembayaran') == 'tunai' ? 'selected' : '' }}>Tunai
                    </option>
                    <option value="qris" {{ request('pembayaran') == 'qris' ? 'selected' : '' }}>QRIS
                    </option>
                </select>

                <button type="submit" class="btn btn-dark"><i class="bx bx-filter"></i> Filter</button>
                <a href="{{ route('pembayaran.index') }}" class="btn btn-outline-secondary">Reset</a>
            </form>

            <div class="card shadow-sm border-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nomor Polisi</th>
                                <th>Metode</th>
                                <th>Jumlah</th>
                                <th>Petugas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y H:i') }}</td>
                                    <td>{{ $item->kendaraanMasuk->dataKendaraan->no_polisi ?? '-' }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $item->pembayaran === 'kompensasi' ? 'danger' : 'success' }}">
                                            {{ ucfirst($item->pembayaran) }}
                                        </span>
                                    </td>
                                    {{-- <td>Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td> --}}
                                    <td>
                                        Rp {{ number_format(optional($item->keuangan)->jumlah ?? 0, 0, ',', '.') }}
                                    </td>

                                    <td>{{ $item->petugas->name ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-muted">Belum ada data pembayaran.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">



    <!-- jQuery & jQuery UI -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <script>
        $(function() {
            $("#petugas").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('autocomplete.petugas') }}",
                        data: {
                            query: request.term
                        },
                        success: function(data) {
                            response(data);
                        }
                    });
                },
                minLength: 1
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
