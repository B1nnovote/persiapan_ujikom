<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
use App\Models\KendaraanMasuk;

Route::get('/dashboard', function () {
    return response()->json([
        'masuk'  => KendaraanMasuk::count(),
        'keluar' => KendaraanKeluar::count(),
        'slot'   => 100 - KendaraanMasuk::count(),
    ]);
});

