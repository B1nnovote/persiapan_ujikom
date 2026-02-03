<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Karcis Parkir</title>
    <style>
        @page {
            size: 58mm auto;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 6px;
            font-family: monospace;
            font-size: 11px;
            white-space: pre;
        }
    </style>
</head>

<body>
  
        ----------------------------
        KARCIS PARKIR
        ----------------------------
        Plat : {{ $data->dataKendaraan->no_polisi }}
        Jenis : {{ ucfirst($data->dataKendaraan->jenis_kendaraan) }}
        Masuk : {{ \Carbon\Carbon::parse($data->waktu_masuk)->format('d-m-Y') }}
        Jam : {{ \Carbon\Carbon::parse($data->waktu_masuk)->format('H:i') }}

        Simpan karcis ini
        Wajib ditunjukkan saat keluar
        ----------------------------
        Parkir Otomatis • Sistem
  
</body>

</html>
