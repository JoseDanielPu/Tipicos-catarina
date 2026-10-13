<?php

use App\Http\Controllers\TipicosController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\EnvioController;

Route::resource('productos', ProductoController::class)->only(['index', 'store', 'destroy']);
Route::resource('ventas', VentaController::class);
Route::get('envios/{envio}/completar', [EnvioController::class, 'completar'])->name('envios.completar');
Route::put('envios/{envio}/completar', [EnvioController::class, 'guardar'])->name('envios.guardar');