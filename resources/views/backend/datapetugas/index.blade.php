<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Petugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- jQuery UI -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

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
                    <h4 class="fw-semibold mb-3 ms-5" style="font-size:30px;">Data Petugas</h4>
                    <div class="d-flex flex-wrap gap-2 me-5 mb-3"> <a href="{{ route('petugas.create') }}"
                            class="btn" style="background-color:#696cff; color:white;">
                            <i class="bx bx-plus"></i> Tambah
                        </a>
                        <a href="{{ route('petugas.export.pdf', request()->query()) }}" class="btn btn-danger">
                            <i class="bx bxs-file-pdf"></i> Export PDF
                        </a>
                        <a href="{{ route('petugas.export.excel', request()->query()) }}" class="btn btn-success">
                            <i class="bx bxs-file-export"></i> Export Excel
                        </a>
                    </div>
                </div>
                <form action="{{ route('petugas.index') }}" method="GET" class="d-flex gap-2 mb-3">
                    <div class="input-group ms-5" style="max-width: 400px;">
                        <input type="text" id="search-petugas" name="search" class="form-control"
                            placeholder="Cari nama petugas..." value="{{ request('search') }}">
                        <button class="btn btn-dark" type="submit"><i class="bx bx-search"></i></button>
                        <a href="{{ route('petugas.index') }}" class="btn btn-outline-secondary ms-2">Reset</a>
                    </div>
                </form>
                <div class="card shadow-sm border-0 col-11">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle text-center mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Foto</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($petugas as $row)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if ($row->foto)
                                                <img src="{{ asset('storage/' . $row->foto) }}" width="50"
                                                    height="50" name="foto" class="rounded-circle"
                                                    alt="Foto Petugas">
                                            @else
                                                <span class="text-muted">Belum ada</span>
                                            @endif
                                        </td>

                                        <td>{{ $row->name }}</td>
                                        <td>{{ $row->email }}</td>
                                        <td class="gap-2">
                                            <div class="btn-group gap-2">
                                                <a href="{{ route('petugas.edit', $row->id) }}"
                                                    class="btn btn-sm btn-warning"><i class="bx bx-edit"></i></a>
                                                <form onsubmit="return confirmDelete(this);"
                                                    action="{{ route('petugas.destroy', $row->id) }}" method="POST"
                                                    style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="d-inline btn btn-danger btn-sm"><i
                                                            class="bx bx-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Belum ada petugas.</td>
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
