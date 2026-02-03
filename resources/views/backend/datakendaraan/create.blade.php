<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Kendaraan</title>

    <!-- Boxicons -->
    <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f5f7;
        }

        .card-custom {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
        }

        .form-label {
            font-weight: 500;
        }

        .btn-primary {
            background-color: #696cff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #5c61e6;
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    @include('layouts.part.sidebar')

    <!-- CONTENT -->
    <section id="content">
        <!-- NAVBAR -->
        @include('layouts.part.navbar')

        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-7">
                    <div class="card card-custom">
                        <div class="card-header btn-primary  text-white fw-semibold">
                            Tambah Data Kendaraan
                        </div>
                        <div class="card-body">
                            <form action="{{ route('datakendaraan.store') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label for="plat_nomor" class="form-label">Plat Nomor</label>
                                    <input type="text" name="no_polisi" id="plat_nomor" class="form-control"
                                        placeholder="Contoh: B 1234 XYZ" required>
                                    @if ($errors->has('no_polisi'))
                                        <div class="text-danger">{{ $errors->first('no_polisi') }}</div>
                                    @endif

                                </div>

                                <div class="mb-3">
                                    <label for="jenis" class="form-label">Jenis Kendaraan</label>
                                    <select name="jenis_kendaraan" id="jenis" class="form-select" required>
                                        <option value="">-- Pilih Jenis --</option>
                                        <option value="mobil">Mobil</option>
                                        <option value="motor">Motor</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="pemilik" class="form-label">Nama Pemilik</label>
                                    <input type="text" name="pemilik" id="pemilik" class="form-control"
                                        placeholder="Nama lengkap" required>
                                </div>

                                <div class="mb-4">
                                    <label for="status_pemilik" class="form-label">Status Pemilik</label>
                                    <select name="status_pemilik" id="status_pemilik" class="form-select" required>
                                        <option value="">-- Pilih Status --</option>
                                        <option value="guru">Guru</option>
                                        <option value="karyawan">Karyawan</option>
                                        <option value="tamu">Tamu</option>
                                    </select>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('datakendaraan.index') }}" class="btn btn-outline-secondary">←
                                        Kembali</a>
                                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
