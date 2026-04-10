<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Kendaraan Keluar</title>

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        body {
            background-color: #f5f5f9;
        }

        .form-section {
            max-width: 600px;
            margin: 40px auto;
        }

        .form-label {
            font-weight: 600;
        }

        .sneat-title {
            color: #696cff;
            font-weight: 600;
        }

        .btn-sneat {
            background-color: #696cff;
            color: white;
            font-weight: 500;
        }

        .btn-sneat:hover {
            background-color: #5a5bd3;
        }

        .select2-container .select2-selection--single {
            height: 38px;
            padding: 4px 8px;
        }
    </style>
</head>

<body>

    @include('layouts.part.sidebar')
    <section id="content">
        @include('layouts.part.navbar')

        <div class="container mt-4">
            <div class="form-section">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h5 class="sneat-title mb-4"><i class="fas fa-sign-out-alt me-2"></i>Form Kendaraan Keluar</h5>

                        @if (session('error'))
                            <div class="alert alert-danger text-center">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('kendaraankeluar.store') }}" method="POST">
                            @csrf

                            {{-- Kendaraan Masuk --}}
                            <div class="mb-3">
                                <label for="id_kendaraan_masuk" class="form-label">Pilih Kendaraan</label>
                                <select name="id_kendaraan_masuk" id="id_kendaraan_masuk"
                                    class="form-select select-search @error('id_kendaraan_masuk') is-invalid @enderror"
                                    required>
                                    <option value="">-- Pilih Kendaraan --</option>
                                    @foreach ($parkirAktif as $item)
                                        <option value="{{ $item->id }}">
                                              {{ $item->kode_tiket }} -
                                            {{ $item->dataKendaraan->no_polisi }} -
                                            {{ ucfirst($item->dataKendaraan->jenis_kendaraan) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_kendaraan_masuk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Waktu Keluar --}}
                            <div class="mb-3">
                                <label for="waktu_keluar" class="form-label">Waktu Keluar</label>
                                @php
                                    $waktuDefault = now()->addMinute()->format('Y-m-d\TH:i');
                                @endphp
                                <input type="datetime-local" name="waktu_keluar" id="waktu_keluar"
                                    class="form-control @error('waktu_keluar') is-invalid @enderror"
                                    value="{{ $waktuDefault }}" min="{{ $waktuDefault }}" required>
                                @error('waktu_keluar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Status Kondisi --}}
                            <div class="mb-4">
                                <label class="form-label">Status Kendaraan</label><br>

                                @php
                                    $statusOptions = [
                                        'baik',
                                        'karcis hilang',
                                        'kerusakan',
                                        'kehilangan',
                                    ];
                                @endphp

                                @foreach ($statusOptions as $status)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="status_kondisi[]"
                                            id="status_{{ $status }}" value="{{ $status }}">
                                        <label class="form-check-label" for="status_{{ $status }}">
                                            {{ ucfirst($status) }}
                                        </label>
                                    </div>
                                @endforeach

                                @error('status_kondisi')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>



                            {{-- Tombol --}}
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('kendaraankeluar.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-sneat">
                                    <i class="fas fa-save me-1"></i> Simpan Data
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select-search').select2({
                placeholder: '-- Pilih Kendaraan --',
                allowClear: true,
                width: '100%'
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
