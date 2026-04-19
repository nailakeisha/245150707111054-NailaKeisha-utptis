<?php

use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;



Route::prefix('items')->group(function () {
    Route::get('/',         [ItemController::class, 'index']);   // GET semua item
    Route::post('/',        [ItemController::class, 'store']);   // POST buat item baru
    Route::get('/{id}',     [ItemController::class, 'show']);    // GET item by ID
    Route::put('/{id}',     [ItemController::class, 'update']);  // PUT update semua field
    Route::patch('/{id}',   [ItemController::class, 'patch']);   // PATCH update sebagian
    Route::delete('/{id}',  [ItemController::class, 'destroy']); // DELETE hapus item
});

Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'Endpoint tidak ditemukan. Cek dokumentasi API.',
    ], 404);
});