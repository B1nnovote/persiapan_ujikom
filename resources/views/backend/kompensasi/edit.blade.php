<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kompensasi</title>

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .btn-label {
            pointer-events: none;
            font-weight: 500;
        }
    </style>
</head>

<body>
    @include('layouts.part.sidebar')

    <section id="content">
        @include('layouts.part.navbar')

        <div class="container col-10 mt-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="btn btn-sm btn-label-primary rounded-pill px-3 py-2 btn-label" style="font-size:30px;">
                    Edit Kompensasi
                </span>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header text-white fw-semibold" style="background-color: #696cff">
                    Formulir Edit Kompensasi
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('kompensasi.update', $kompensasi->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Plat Nomor</label>
                            <input type="text" class="form-control"
                                value="{{ $kompensasi->kendaraanMasuk->dataKendaraan->no_polisi }}" readonly disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jumlah Kompensasi (Rp)</label>
                            <input type="number" name="jumlah"
                                class="form-control @error('jumlah') is-invalid @enderror"
                                value="{{ $kompensasi->jumlah }}" required>
                            @error('jumlah')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea rows="3" class="form-control" readonly>{{ $kompensasi->keterangan }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('kompensasi.index') }}" class="btn btn-outline-secondary">
                                <i class="bx bx-arrow-back"></i> Kembali
                            </a>
                            <button type="submit" class="btn" style="background-color: #696cff; color:white;">
                                <i class="bx bx-save"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
