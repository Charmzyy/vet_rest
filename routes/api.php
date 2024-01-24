<?php

use App\Http\Controllers\AdminController;
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

Route::middleware(['auth:sanctum', 'is_admin'])->group(function () {
    Route::post('create/roles',[AdminController::class,'createroles']);
    Route::post('create/newusers',[AdminController::class,'createAccounts']);

});

Route::controller(AuthController::class)->group(function () {

Route::post('register','register');
Route::post('login','login');


});
