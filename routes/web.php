<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataKendaraanController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\KompensasiController;
use App\Http\Controllers\ParkirKeluarController;
use App\Http\Controllers\ParkirMasukController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\StokLahanController;
use App\Http\Middleware\Admin;
use App\Http\Middleware\Petugas;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth::routes(['register' => false]);
Auth::routes();

Route::get('/frontend', function () {
    return view('frontend.index');
})->middleware('auth');

// filter-export data kendaraan
Route::get('/datakendaraan/autocomplete', [DataKendaraanController::class, 'autocomplete'])->name('datakendaraan.autocomplete');
Route::get('datakendaraan/export/pdf', [DataKendaraanController::class, 'exportPdf'])->name('datakendaraan.export.pdf');
Route::get('/datakendaraan/export/excel', [DataKendaraanController::class, 'exportExcel'])->name('datakendaraan.export.excel');

// filter-export data petugas
Route::get('petugas/autocomplete', [PetugasController::class, 'autocomplete'])->name('petugas.autocomplete');
Route::get('/petugas/export/pdf', [PetugasController::class, 'exportPdf'])->name('petugas.export.pdf');
Route::get('/petugas/export/excel', [PetugasController::class, 'exportExcel'])->name('petugas.export.excel');

// export data kendaraan masuk
Route::get('/kendaraanmasuk/export/pdf', [ParkirMasukController::class, 'exportPdf'])->name('kendaraanmasuk.export.pdf');
Route::get('/kendaraanmasuk/export/excel', [ParkirMasukController::class, 'exportExcel'])->name('kendaraanmasuk.export.excel');

// export data kendaran keluar
Route::get('/kendaraankeluar/export/pdf', [ParkirKeluarController::class, 'exportPdf'])->name('kendaraankeluar.export.pdf');
Route::get('/kendaraankeluar/export/excel', [ParkirKeluarController::class, 'exportExcel'])->name('kendaraankeluar.export.excel');

// filter-export pembayaran
Route::get('/autocomplete/petugas', [PembayaranController::class, 'autocompletePetugas'])->name('autocomplete.petugas');
Route::get('/pembayaran/export/pdf', [PembayaranController::class, 'exportPdf'])->name('pembayaran.export.pdf');
Route::get('/pembayaran/export/excel', [PembayaranController::class, 'exportExcel'])->name('pembayaran.export.excel');

// filter-export kompensasi

Route::get('/kompensasi/export/pdf', [KompensasiController::class, 'exportPdf'])->name('kompensasi.export.pdf');
Route::get('/kompensasi/export/excel', [KompensasiController::class, 'exportExcel'])->name('kompensasi.export.excel');
Route::get('/autocomplete-nopol', [KompensasiController::class, 'autocompleteNopol'])->name('kompensasi.autocomplete-nopol');

// filter-export keuangan
Route::get('/keuangan/export/pdf', [KeuanganController::class, 'exportPdf'])->name('keuangan.export.pdf');
Route::get('/keuangan/export/excel', [KeuanganController::class, 'exportExcel'])->name('keuangan.export.excel');

Route::middleware(['auth'])->group(function () {

    Route::get('/backend', [DashboardController::class, 'adminDashboard'])
        ->middleware(Admin::class)
        ->name('backend.index');

    Route::get('/frontend', [DashboardController::class, 'petugasDashboard'])
        ->middleware(Petugas::class)
        ->name('frontend.index');

    Route::resource('datakendaraan', DataKendaraanController::class);

    Route::resource('stok', StokLahanController::class);

    Route::get('/kendaraanmasuk/{id}/karcis', [ParkirMasukController::class, 'karcis'])->name('kendaraanmasuk.karcis.karcis');
    Route::get('/kendaraanmasuk/{id}/karcis-pdf', [ParkirMasukController::class, 'cetakPDF'])->name('kendaraanmasuk.karcis.pdf');
    Route::resource('kendaraanmasuk', ParkirMasukController::class);

    Route::get('/kendaraankeluar', [ParkirKeluarController::class, 'index'])->name('kendaraankeluar.index');
    Route::get('/kendaraankeluar/create', [ParkirKeluarController::class, 'create'])->name('kendaraankeluar.create');
    Route::post('/kendaraankeluar', [ParkirKeluarController::class, 'store'])->name('kendaraankeluar.store');
    Route::get('/kendaraankeluar/{id}', [ParkirKeluarController::class, 'show'])->name('kendaraankeluar.show');
    Route::get('/kendaraankeluar/{id}/edit', [ParkirKeluarController::class, 'edit'])->name('kendaraankeluar.edit');
    Route::put('/kendaraankeluar/{id}', [ParkirKeluarController::class, 'update'])->name('kendaraankeluar.update');
    Route::delete('/kendaraankeluar/{id}', [ParkirKeluarController::class, 'destroy'])->name('kendaraankeluar.destroy');

    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::get('/pembayaran/{idKeluar}/create', [PembayaranController::class, 'create'])->name('pembayaran.create'); // ← ini yang dipanggil dari redirect()
    Route::post('/pembayaran', [PembayaranController::class, 'store'])->name('pembayaran.store');

    Route::get('/keuangan', [KeuanganController::class, 'index'])->name('keuangan.index');
    Route::post('/keuangan', [KeuanganController::class, 'store'])->name('keuangan.store');
    Route::get('/keuangan/{id}', [KeuanganController::class, 'show'])->name('keuangan.show');
    Route::delete('/keuangan/{id}', [KeuanganController::class, 'destroy'])->name('keuangan.destroy');

    Route::get('/kompensasi/create/{idKendaraanMasuk}', [KompensasiController::class, 'create'])->name('kompensasi.create');
    Route::post('/kompensasi/store', [KompensasiController::class, 'store'])->name('kompensasi.store');
    Route::get('/kompensasi/{id}/edit', [KompensasiController::class, 'edit'])->name('kompensasi.edit');
    Route::put('/kompensasi/{id}', [KompensasiController::class, 'update'])->name('kompensasi.update');
    Route::get('/keluar/cek/{id}', [ParkirKeluarController::class, 'cekKondisi'])->name('keluar.cekKondisi');

});

Route::middleware(['auth', Admin::class])->group(function () {
    Route::get('/kompensasi', [KompensasiController::class, 'index'])->name('kompensasi.index');
    Route::get('/kompensasi/{id}/approval', [KompensasiController::class, 'approval'])
        ->name('kompensasi.approval');
    Route::put('/kompensasi/{id}/approve', [KompensasiController::class, 'approve'])->name('kompensasi.approve');
    Route::put('/kompensasi/{id}/reject', [KompensasiController::class, 'reject'])->name('kompensasi.reject');

    Route::resource('petugas', PetugasController::class);

});
