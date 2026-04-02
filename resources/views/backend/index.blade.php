<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- My CSS -->
    <title>Dashboard Admin</title>

    <style>
        .head-title {
            padding: 30px 25px 0 25px;
            background-color: transparent;
        }

        .head-title .left h1 {
            font-size: 50px;
            font-weight: 600;
            color: #2c2c2c;
            margin-bottom: 6px;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            list-style: none;
            padding: 0;
            margin: 0;
            gap: 6px;
        }

        .breadcrumb li a {
            color: #777;
            font-size: 14px;
            text-decoration: none;
        }

        .breadcrumb li a.active {
            color: #6a11cb;
            font-weight: 500;
        }

        .breadcrumb li i {
            color: #aaa;
            font-size: 26px;
        }

        .welcome-text {
            font-size: 45px;
            font-weight: 400;
            color: #444;
            margin: 15px 25px;
        }

        .welcome-text strong {
            color: #6a11cb;
            font-weight: 600;
        }


        .box-info {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-top: 2rem;
            width: 95%;
        }


        .box-info li {
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-radius: 12px;
            width: 100%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            color: white;

        }

        .box-info li i {
            font-size: 2.5rem;
            padding: 1rem;
            border-radius: 50%;
            min-width: 60px;
            text-align: center;
            background: rgba(255, 255, 255, 0.2);
        }

        .box-info li .text h3 {
            margin: 0;
            font-size: 1.4rem;
        }

        .box-info li .text p {
            margin: 0;
            font-size: 0.9rem;
        }

        .bg-1 {
            background: #3b82f6;
        }

        .bg-2 {
            background: #10b981;
        }

        .bg-3 {
            background: #f59e0b;
        }

        .bg-4 {
            background: #afbeb483;
        }

        .bg-5 {
            background: #8b5cf6;
        }
    </style>

</head>

<body>

    <!-- SIDEBAR -->
    @include('layouts.part.sidebar')
    <!-- END SIDEBAR -->

    <!-- CONTENT -->
    <section id="content">
        <!-- NAVBAR -->
        @include('layouts.part.navbar')
        <!-- END NAVBAR -->

        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Dashboard</h1>
                    <ul class="breadcrumb">
                        <li><a href="#">Dashboard</a></li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li><a class="active" href="#">Admin</a></li>
                    </ul>
                </div>
            </div>

            <p style="font-size:30px; margin-left:25px;">Selamat datang, <strong>{{ Auth::user()->name }}</strong></p>


            <ul class="box-info">


                <li class="bg-1">
                    <i class='bx bx-log-in-circle'></i>
                    <span class="text">
                        <h3>{{ $totalMasuk }}</h3>
                        <p>Sedang Terparkir</p>
                    </span>
                </li>
                <li class="bg-2">
                    <i class='bx bx-log-out-circle'></i>
                    <span class="text">
                        <h3>{{ $totalKeluar }}</h3>
                        <p>Kendaraan Keluar</p>
                    </span>
                </li>
                <li class="bg-3">
                    <i class='bx bx-spreadsheet'></i>
                    <span class="text">
                        <h3>{{ $totalKendaraan }}</h3>
                        <p>Total Data Kendaraan</p>
                    </span>
                </li>

                <li class="bg-4">
                    <i class='bx bx-car' style="color:black; background:#7953c293;"></i>
                    <span class="text">
                        <h3>{{ $sisaMobil }}</h3>
                        <p>Sisa Slot Mobil</p>
                    </span>
                </li>
                <li class="bg-5">
                    <i class='bx bx-cycling' style="color:black; background:#8a5cf659;"></i>
                    <span class="text">
                        <h3>{{ $sisaMotor }}</h3>
                        <p>Sisa Slot Motor</p>
                    </span>
                </li>
                <li class="bg-2">
                    <i class='bx bx-wallet'></i>
                    <span class="text">
                        <h3>Rp {{ number_format($totalTunai, 0, ',', '.') }}</h3>
                        <p>Total Tunai</p>
                    </span>
                </li>

                <li class="bg-5">
                    <i class='bx bx-qr'></i>
                    <span class="text">
                        <h3>Rp {{ number_format($totalQris, 0, ',', '.') }}</h3>
                        <p>Total QRIS</p>
                    </span>
                </li>

            </ul>

            <div style="display: flex; gap: 2rem; margin-top: 3rem; flex-wrap: wrap;">
                <!-- Grafik Masuk -->
                <div
                    style="
        flex: 1;
        min-width: 400px;
        background: white;
        padding: 1.5rem 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        max-width: 600px;
    ">
                    <h2 style="margin-bottom: 1rem; font-size: 1.25rem; color: #111;">Grafik Kendaraan Masuk per Bulan
                    </h2>
                    <canvas id="grafikMasuk"></canvas>
                </div>


                <!-- Grafik Keuangan -->

                <div id="grafik-keuangan"
                    style="
        flex: 1;
        min-width: 400px;
        background: white;
        padding: 1.5rem 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        max-width: 600px;
    ">
                    <form method="GET" action="{{ url('/backend') }}" style="margin-bottom: 1rem;">
                        <div style="display:flex; justify-content:flex-start;">
                            <div style="display:flex; flex-direction:column; gap:4px;">
                                <label
                                    style="
                font-size: 13px;
                color: #6b7280;
                font-weight: 500;
            ">
                                    Rentang Waktu
                                </label>

                                <select name="range" onchange="handleRangeChange(this)"
                                    style="
                    padding: 8px 14px;
                    border-radius: 8px;
                    border: 1px solid #e5e7eb;
                    font-size: 14px;
                    background-color: #fff;
                    color: #111;
                    min-width: 150px;
                    cursor: pointer;
                ">
                                    <option value="harian" {{ request('range') == 'harian' ? 'selected' : '' }}>Harian
                                    </option>
                                    <option value="mingguan" {{ request('range') == 'mingguan' ? 'selected' : '' }}>
                                        Mingguan</option>
                                    <option value="bulanan" {{ request('range') == 'bulanan' ? 'selected' : '' }}>
                                        Bulanan</option>
                                    <option value="tahunan" {{ request('range') == 'tahunan' ? 'selected' : '' }}>
                                        Tahunan</option>
                                </select>
                            </div>
                        </div>
                    </form>


                    {{-- <h2>{{ $judulGrafik }}</h2> --}}
                    <canvas id="grafikKeuangan"></canvas>
                </div>
            </div>

        </main>
        <!-- END MAIN -->
    </section>
    <!-- END CONTENT -->

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        /* ================= GRAFIK KENDARAAN MASUK ================= */
        const ctxMasuk = document.getElementById('grafikMasuk').getContext('2d');

        new Chart(ctxMasuk, {
            type: 'line',
            data: {
                labels: {!! json_encode($labelsMasuk) !!},
                datasets: [{
                    label: 'Jumlah Kendaraan Masuk',
                    data: {!! json_encode($dataMasuk) !!},
                    borderColor: '#8b5cf6',
                    backgroundColor: 'rgba(139, 92, 246, 0.2)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        /* ================= GRAFIK KEUANGAN ================= */
        const ctxKeuangan = document.getElementById('grafikKeuangan').getContext('2d');

        new Chart(ctxKeuangan, {
            type: 'line',
            data: {
                labels: {!! json_encode($labelsKeuangan) !!},
                datasets: [{
                        label: 'Pemasukan',
                        data: {!! json_encode($dataPemasukan) !!},
                        borderColor: '#22c55e',
                        tension: 0.4
                    },
                    {
                        label: 'Pengeluaran',
                        data: {!! json_encode($dataPengeluaran) !!},
                        borderColor: '#ef4444',
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
    </script>

    <script>
        function handleRangeChange(select) {
            localStorage.setItem('scrollToGrafik', 'true');
            select.form.submit();
        }

        window.addEventListener('load', function() {
            if (localStorage.getItem('scrollToGrafik')) {
                const grafik = document.getElementById('grafik-keuangan');
                if (grafik) {
                    grafik.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
                localStorage.removeItem('scrollToGrafik');
            }
        });
    </script>

</body>

</html>
