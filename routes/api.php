<?php

use App\Http\Controllers\AuthController;
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
