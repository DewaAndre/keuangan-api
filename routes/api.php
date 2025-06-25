<?php

use App\Http\Controllers\API\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Models\User;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/test', [LoginController::class, 'test']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/user', function () {
    return response()->json([
        'status' => 'success',
        'data' => User::all()->makeHidden(['password', 'remember_token']),
    ]);
});

Route::get('/keuangan', [ApiController::class, 'getKeuangan']);
Route::match(['get', 'post'], '/hutang', [ApiController::class, 'tambahHutang']);
Route::match(['get', 'post'], '/register', [ApiController::class, 'register']);
Route::match(['get', 'post'], '/pemasukan', [ApiController::class, 'tambahPemasukan']);
Route::match(['get', 'post'], '/pengeluaran', [ApiController::class, 'tambahPengeluaran']);
