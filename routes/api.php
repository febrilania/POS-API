<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UnitController;
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

Route::middleware('auth:sanctum')->group(function(){
    //kategori
    Route::apiResource('categories', CategoryController::class);

    //unit
    Route::apiResource('units', UnitController::class);

    //item
    
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login',[AuthController::class,'login']);