<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\QrController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/ping', function () {
    return response()->json(['pong' => true]);
});


Route::post('/qrcode/create', [QrController::class, 'create']);
Route::get('/qrcode/status/{id}', [QrController::class, 'status']);
Route::post('/qrcode/validate', [QrController::class, 'validatePayment']);


Route::post('/nfc/create', [QrController::class, 'createNfc']);
Route::post('/nfc/validate', [QrController::class, 'validateNfc']);


