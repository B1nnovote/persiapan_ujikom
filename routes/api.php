<?php

use App\Models\DataKendaraan;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
});

use App\Models\KendaraanKeluar;
use App\Models\StokLahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {

    $mobil = StokLahan::where('jenis_kendaraan', 'mobil')->first();
    $motor = StokLahan::where('jenis_kendaraan', 'motor')->first();

    return response()->json([

        'keluar'     => KendaraanKeluar::count(),
        'total'      => DataKendaraan::count(),

        'slot_mobil' => $mobil ? ($mobil->total_slot - $mobil->terpakai) : 0,
        'slot_motor' => $motor ? ($motor->total_slot - $motor->terpakai) : 0,
    ]);
});

Route::get('/kendaraan', function () {
    return response()->json(DataKendaraan::all());
});


use App\Models\User;

Route::get('/petugas', function () {
    return User::select('id', 'name', 'email', 'isAdmin')->get();
});
