<?php

use App\Http\Controllers\CustomerAddressController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/users', [UserController::class, 'store'])->middleware('auth:api');
Route::patch('/users/{id}', [UserController::class, 'update'])->middleware('auth:api');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->middleware('auth:api');
Route::post('/users/login', [UserController::class, 'login']);
Route::get('/users/{id}', [UserController::class, 'show'])->middleware('auth:api');
Route::get('/users', [UserController::class, 'index'])->middleware('auth:api');


Route::post('/customers', [CustomerController::class, 'store'])->middleware('auth:api');
Route::patch('/customers/{id}', [CustomerController::class, 'update'])->middleware('auth:api');
Route::delete('/customers/{id}', [CustomerController::class, 'destroy'])->middleware('auth:api');
Route::get('/customers/{id}', [CustomerController::class, 'show'])->middleware('auth:api');
Route::get('/customers', [CustomerController::class, 'index'])->middleware('auth:api');

Route::prefix('customers/{customerId}')->group(function () {
    Route::post('/addresses', [CustomerAddressController::class, 'store'])->middleware('auth:api');
    Route::patch('/addresses/{id}', [CustomerAddressController::class, 'update'])->middleware('auth:api');
    Route::get('/addresses', [CustomerAddressController::class, 'index'])->middleware('auth:api');
    Route::get('/addresses/{id}', [CustomerAddressController::class, 'show'])->middleware('auth:api');
    Route::delete('/addresses/{id}', [CustomerAddressController::class, 'destroy'])->middleware('auth:api');
});
