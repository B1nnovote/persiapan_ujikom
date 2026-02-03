<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approval Kompensasi</title>

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
                <span class="btn btn-sm btn-label-primary rounded-pill px-3 py-2 btn-label"
                    style="font-size:30px;">
                    Approval Kompensasi
                </span>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header text-white fw-semibold" style="background-color: #696cff">
                    Detail Pengajuan Kompensasi
                </div>

                <div class="card-body p-4">

                    {{-- Plat Nomor --}}
                    <div class="mb-3">
                        <label class="form-label">Plat Nomor</label>
                        <input type="text" class="form-control"
                            value="{{ $kompensasi->kendaraanMasuk->dataKendaraan->no_polisi }}"
                            readonly disabled>
                    </div>

                    {{-- Jenis Kendaraan --}}
                    <div class="mb-3">
                        <label class="form-label">Jenis Kendaraan</label>
                        <input type="text" class="form-control"
                            value="{{ ucfirst($kompensasi->kendaraanMasuk->dataKendaraan->jenis_kendaraan) }}"
                            readonly disabled>
                    </div>

                    {{-- FORM --}}
                    <form id="formApproval"
                        action="{{ route('kompensasi.approve', $kompensasi->id) }}"
                        method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Jumlah --}}
                        <div class="mb-3">
                            <label class="form-label">Jumlah Kompensasi (Rp)</label>
                            <input type="number" name="jumlah"
                                class="form-control @error('jumlah') is-invalid @enderror"
                                value="{{ $kompensasi->jumlah }}" required>
                            @error('jumlah')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Keterangan --}}
                        <div class="mb-3">
                            <label class="form-label">Keterangan Pengajuan</label>
                            <textarea rows="3" class="form-control" readonly disabled>{{ $kompensasi->keterangan }}</textarea>
                        </div>

                        {{-- Catatan Admin --}}
                        <div class="mb-4">
                            <label class="form-label">Catatan Admin</label>
                            <textarea name="catatan_admin" rows="3"
                                class="form-control"
                                placeholder="Opsional (wajib jika ditolak)"></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('kompensasi.index') }}" class="btn btn-outline-secondary">
                                <i class="bx bx-arrow-back"></i> Kembali
                            </a>

                            <div class="d-flex gap-2">
                                <button type="button" id="btnReject" class="btn btn-danger">
                                    <i class="bx bx-x-circle"></i> Tolak
                                </button>

                                <button type="button" id="btnApprove"
                                    class="btn" style="background-color:#696cff; color:white;">
                                    <i class="bx bx-check-circle"></i> Setujui
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const form = document.getElementById('formApproval');

        document.getElementById('btnApprove').addEventListener('click', () => {
            Swal.fire({
                title: 'Setujui Kompensasi?',
                text: 'Data akan diproses dan dicatat ke keuangan.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Setujui',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#696cff'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.action = "{{ route('kompensasi.approve', $kompensasi->id) }}";
                    form.submit();
                }
            });
        });

        document.getElementById('btnReject').addEventListener('click', () => {
            const catatan = document.querySelector('[name="catatan_admin"]').value;

            if (!catatan) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Catatan wajib diisi',
                    text: 'Alasan penolakan harus diisi!'
                });
                return;
            }

            Swal.fire({
                title: 'Tolak Kompensasi?',
                text: 'Kompensasi akan ditolak.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Tolak',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc3545'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.action = "{{ route('kompensasi.reject', $kompensasi->id) }}";
                    form.submit();
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
