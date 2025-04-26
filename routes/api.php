<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

Route::post('/register',[AuthController::class, 'register']);
Route::post('/login',[AuthController::class, 'login']);


Route::get('/auth/verify-email/{id}/{hash}', [AuthController::class, 'verifyEmail'])
->middleware('signed')->name('verification.verify');
Route::post('/resend-verification', [AuthController::class, 'resendVerification']);

Route::middleware('auth:api')->get('/debug-user', function () {
    return response()->json(auth()->user());
});

Route::group(['middleware' => ['auth:api']], function() {
    Route::post('/logout',[AuthController::class, 'logout']);
    // Shared route for both admin and user
    Route::get('/transactions', [TransactionController::class, 'getAll']);
     // Providers
     Route::get('/providers', [ProviderController::class, 'getProvider']);
     Route::get('/providers/{id}', [ProviderController::class, 'getProviderByID']);
     // Products
     Route::get('/products', [ProductController::class, 'getProducts']);
     Route::get('/products/{id}', [ProductController::class, 'getProductsByID']);
     

    // Additional routes for specific roles
    Route::group(['middleware' => ['role:admin']], function() {
        Route::put('/transactions/{id}', [TransactionController::class, 'update']);
        Route::delete('/transactions/{id}', [TransactionController::class, 'delete']);
        Route::post('/transactions', [TransactionController::class, 'insert']);
        Route::post('/providers', [ProviderController::class, 'insert']);
        Route::post('/products', [ProductController::class, 'insert']);
    });

    Route::group(['middleware' => ['role:user']], function() {
        Route::get('/contacts', action: [UserController::class, 'contacts']);
        Route::get('/contacts/{phone}', action: [UserController::class, 'contacts']);
    });
});

