<?php

use App\Http\Controllers\API\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Models\User;
use App\Http\Controllers\API\DiagramController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\API\WishlistController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\Api\DanaDaruratController;
use App\Http\Controllers\Api\CetakController;
use App\Http\Controllers\PremiumController;
use App\Http\Controllers\Api\MidtransController;
use App\Http\Controllers\Api\PembayaranController;




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

Route::post('/login', [ApiController::class, 'login']);
Route::get('/login', [ApiController::class, 'login']);


// home


Route::get('/home', [HomeController::class, 'index']);

Route::get('/diagram', [DiagramController::class, 'getDiagram']);


Route::get('/user/{id}', [UserController::class, 'show']);
Route::put('/user/{id}', [UserController::class, 'update']);
Route::put('/reset-password', [UserController::class, 'resetPassword']);


Route::get('/cetak', [CetakController::class, 'cetak']);

Route::get('/dana-darurat/{id_user}', [DanaDaruratController::class, 'index']);
Route::post('/dana-darurat', [DanaDaruratController::class, 'store']);


Route::get('/wishlist', [WishlistController::class, 'index']);
Route::post('/wishlist', [WishlistController::class, 'store']);


Route::post('/premium', [PremiumController::class, 'beliPaket']);


Route::post('/midtrans/token', [MidtransController::class, 'getSnapToken']);
Route::post('/midtrans/webhook', [MidtransController::class, 'handleNotification']);
Route::post('/pembayaran', [PembayaranController::class, 'bayar']);
