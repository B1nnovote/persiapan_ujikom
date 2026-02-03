<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kendaraan</title>

    <!-- jQuery & jQuery UI -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Boxicons & Bootstrap -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    @include('layouts.part.sidebar')

    <section id="content">
        @include('layouts.part.navbar')
        <main>
            <div class="container mt-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="btn btn-sm btn-label-primary rounded-pill px-3 py-2"
                        style="pointer-events: none; font-size:30px;">
                        Data Kendaraan
                    </span>
                    <div class="d-flex gap-2">
                        <a href="{{ route('datakendaraan.create') }}" class="btn"
                            style="background-color:#696cff; color:white;">
                            <i class="bx bx-plus"></i> Tambah
                        </a>

                        <a href="{{ route('datakendaraan.export.pdf', request()->query()) }}"
                            class="btn btn-danger">
                            <i class="bx bxs-file-pdf"></i> Export PDF
                        </a>

                        <a href="{{ route('datakendaraan.export.excel', request()->query()) }}"
                            class="btn btn-success">
                            <i class="bx bxs-file-export"></i> Export Excel
                        </a>
                    </div>
                </div>

                <!-- Search -->
                <form action="{{ route('datakendaraan.index') }}" method="GET" class="mb-3">
                    <div class="input-group" style="max-width: 500px;">
                        <input type="text" id="search-kendaraan" name="search" class="form-control"
                            placeholder="Cari plat nomor, jenis, atau status pemilik..."
                            value="{{ request('search') }}">
                        <button class="btn btn-dark" type="submit"><i class="bx bx-search"></i></button>
                        <a href="{{ route('datakendaraan.index') }}" class="btn btn-outline-secondary ms-2">Reset</a>
                    </div>
                </form>

                <!-- Table -->
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Plat Nomor</th>
                                        <th>Jenis</th>
                                        <th>Pemilik</th>
                                        <th>Status Pemilik</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach ($dataKendaraan as $data)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $data->no_polisi }}</td>
                                            <td>{{ ucfirst($data->jenis_kendaraan) }}</td>
                                            <td>{{ $data->pemilik ?? '-' }}</td>
                                            <td>
                                                <span class="badge bg-info text-dark">
                                                    {{ ucfirst($data->status_pemilik) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('datakendaraan.edit', $data->id) }}"
                                                        class="btn btn-sm btn-warning"><i class="bx bx-edit"></i></a>
                                                    <span style=" font-size:20px;">|</span>
                                                    <form onsubmit="return confirmDelete(this);"
                                                        action="{{ route('datakendaraan.destroy', $data->id) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf @method('DELETE')
                                                        <button class="btn btn-sm btn-danger"><i
                                                                class="bx bx-trash"></i></button>
                                                    </form>
                                                    <span style=" font-size:20px;">|</span>
                                                    <a href="/datakendaraan/{{ $data->id }}"
                                                        class="btn btn-sm btn-info"><i class="bx bx-show"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if ($dataKendaraan->isEmpty())
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Belum ada data kendaraan.
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </section>

    <script>
        // Autocomplete
        $(function() {
            $("#search-kendaraan").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('datakendaraan.autocomplete') }}",
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

        // SweetAlert Session
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
