<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Karcis Parkir</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background-color: #f5f5f9;
      font-family: monospace;
      padding: 20px 10px;
      text-align: center;
    }

    .karcis-box {
      border: 1px dashed #999;
      padding: 20px 14px;
      width: 20%;
      margin: auto;
      background-color: #fff;
      font-size: 12px;
      line-height: 1.4;
    }

    .karcis-title {
      font-weight: 700;
      font-size: 13px;
      letter-spacing: 1px;
      margin-bottom: 8px;
    }

    .divider {
      border-top: 1px dashed #999;
      margin: 6px 0;
    }

    .karcis-table {
      width: 100%;
      text-align: left;
      margin-bottom: 6px;
    }

    .karcis-table td {
      padding: 1px 0;
      vertical-align: top;
    }

    .karcis-table td:first-child {
      width: 50px;
    }

    .info {
      font-size: 10px;
      margin-top: 6px;
      line-height: 1.3;
    }

    .btn-group-custom {
      margin-top: 16px;
    }
  </style>
</head>
<body>

  <div class="karcis-box shadow-sm">
    <div class="divider"></div>
    <div class="karcis-title">KARCIS PARKIR</div>
    <div class="divider"></div>

    <table class="karcis-table">
      <tr>
        <td>Plat</td>
        <td>: {{ $data->dataKendaraan->no_polisi }}</td>
      </tr>
      <tr>
        <td>Jenis</td>
        <td>: {{ ucfirst($data->dataKendaraan->jenis_kendaraan) }}</td>
      </tr>
      <tr>
        <td>Masuk</td>
        <td>: {{ \Carbon\Carbon::parse($data->waktu_masuk)->format('d-m-Y') }}</td>
      </tr>
      <tr>
        <td>Jam</td>
        <td>: {{ \Carbon\Carbon::parse($data->waktu_masuk)->format('H:i') }}</td>
      </tr>
    </table>

    <div class="info">
      Simpan karcis ini<br>
      Wajib ditunjukkan saat keluar
    </div>

    <div class="divider"></div>
    <div class="info">Parkir Otomatis • Sistem</div>
  </div>

  <div class="btn-group-custom text-center">
    <a href="{{ route('kendaraanmasuk.karcis.pdf', $data->id) }}" class="btn btn-sm btn-primary">
      Cetak
    </a>
    <a href="{{ route('kendaraanmasuk.index') }}" class="btn btn-sm btn-outline-secondary">
      Kembali
    </a>
  </div>

</body>
</html>
