<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FavProductController;
use App\Http\Controllers\KonsumenController;
use App\Http\Controllers\PengusahaController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\TokoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// login regis
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});

// logout
Route::group(['prefix' => 'auth', 'middleware' => ['auth:sanctum']], function () {
    //logout
    Route::get('logout', [AuthController::class, 'logout']);
    // change password
    Route::put('changePassword', [AuthController::class, 'changePassword']);
});

//user
Route::group(['prefix' => 'user', 'middleware' => ['auth:sanctum']], function () {
    // get data user login
    Route::get('dataLogin', [AuthController::class, 'userLogin']);
    // update data user login
    Route::put('update', [AuthController::class, 'updateUserLogin']);
    // upload or update profile_picture
    Route::post('uploadProfilePicture', [AuthController::class, 'uploadProfilePicture']);
    // delete profile_picture
    Route::delete('deleteProfilePicture', [AuthController::class, 'deleteProfilePicture']);
    // upload or update background_picture
    Route::post('uploadBackgroundPicture', [AuthController::class, 'uploadBackgroundPicture']);
    // delete background_picture
    Route::delete('deleteBackgroundPicture', [AuthController::class, 'deleteBackgroundPicture']);
});

// toko
Route::group(['prefix' => 'toko', 'middleware' => ['auth:sanctum']], function () {
    // get data toko
    Route::get('data', [TokoController::class, 'index']);
    // get data toko by id
    Route::get('data/{id}', [TokoController::class, 'show']);
    // create toko
    Route::post('create', [TokoController::class, 'store']);
    // update toko
    Route::post('update/{id}', [TokoController::class, 'update']);
    // delete toko
    Route::delete('delete/{id}', [TokoController::class, 'destroy']);
    // get toko by pengusaha
    Route::get('dataByPengusaha', [TokoController::class, 'getTokoByPengusaha']);
});

// produk
Route::group(['prefix' => 'produk', 'middleware' => ['auth:sanctum']], function () {
    // get data produk by id
    Route::get('data/{id}', [ProdukController::class, 'show']);
    // create produk
    Route::post('create', [ProdukController::class, 'store']);
    // update produk
    Route::post('update/{id}', [ProdukController::class, 'update']);
    // delete produk
    Route::delete('delete/{id}', [ProdukController::class, 'destroy']);
});

// fav_produk
Route::group(['prefix' => 'fav', 'middleware' => ['auth:sanctum']], function () {
    // get data fav_produk
    Route::get('data', [FavProductController::class, 'index']);
    // post fav
    Route::post('create/{id}', [FavProductController::class, 'store']);
});

// feed
Route::group(['prefix' => 'feed', 'middleware' => ['auth:sanctum']], function () {
    // get data produk
    Route::get('data', [ProdukController::class, 'index']);
});

// konsumen
Route::group(['prefix' => 'admin', 'middleware' => ['auth:sanctum']], function () {
    // get data customer
    Route::get('customer', [KonsumenController::class, 'customer']);
    // get data oengusaha
    Route::get('pengusaha', [PengusahaController::class, 'pengusaha']);
    // delete user
    Route::delete('delete/{id}', [AuthController::class, 'destroy']);
});
