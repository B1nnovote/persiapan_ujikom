<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Dashboard Petugas</title>
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
            background: #30e76d83;
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
                        <li><a class="active" href="#">Petugas</a></li>
                    </ul>
                </div>
            </div>

            @if (session('success'))
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: '{{ session('success') }}',
                        confirmButtonColor: '#6a11cb'
                    });
                </script>
            @endif



            <p style="font-size:25px; margin-left:25px;">Selamat datang, <strong>{{ Auth::user()->name }}</strong></p>


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
                    <i class='bx bx-car' style="color:black; background:#10b98193;"></i>
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
            </ul>

            <div
                style="
	margin-top: 3rem;
	background: white;
	padding: 1.5rem 2rem;
	border-radius: 12px;
	box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
	width: 60%;
	hight:150px;
	max-width: 1000px;
	margin-right: auto;">
                <h2 style="margin-bottom: 1rem; font-size: 1.25rem; color: #111;">Grafik Kendaraan Masuk per Bulan</h2>
                <canvas id="grafikMasuk"></canvas>
            </div>



        </main>
        <!-- END MAIN -->
    </section>
    <!-- END CONTENT -->

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('grafikMasuk').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(139, 92, 246, 0.5)'); // ungu muda
        gradient.addColorStop(1, 'rgba(139, 92, 246, 0.1)'); // ungu transparan

        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [{
                    label: 'Jumlah Kendaraan Masuk',
                    data: {!! json_encode($dataMasuk) !!},
                    backgroundColor: gradient,
                    borderColor: '#8b5cf6',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#8b5cf6'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f3f4f6'
                        }
                    },
                    x: {
                        grid: {
                            color: '#f3f4f6'
                        }
                    }
                }
            }
        });
    </script>

</body>

</html>
