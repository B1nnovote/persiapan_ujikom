<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stok Lahan Parkir</title>
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
                    <h4 class="fw-semibold mb-3 ms-5" style="font-size:30px;">Stok Lahan Parkir</h4>
                </div>

                <div class="card shadow-sm border-0 col-11">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle text-center mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Jenis Kendaraan</th>
                                    <th>Total Slot</th>
                                    <th>Terpakai</th>
                                    <th>Sisa</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($stok as $item)
                                    <tr>
                                        <td>{{ ucfirst($item->jenis_kendaraan) }}</td>
                                        <td>{{ $item->total_slot }}</td>
                                        <td>{{ $item->terpakai }}</td>
                                        <td>{{ $item->total_slot - $item->terpakai }}</td>
                                        <td>
                                            @if (auth()->user()->isAdmin == 1)
                                            <a href="{{ route('stok.edit', $item->id) }}" class="btn btn-sm"
                                                style="background-color:#ffdc69; color:black">
                                                <i class="bx bx-edit-alt"></i>


{{--                                               
                                                    <a href="{{ route('stok.create') }}" class="btn ms-3"
                                                        style="background-color:#696cff; color:white;">
                                                        <i class="bx bx-plus"></i> Tambah --}}
                                                @endif
{{-- 
                                            </a> --}}
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Belum ada data stok lahan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </center>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
