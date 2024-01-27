<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;

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
//admin
Route::middleware(['auth:sanctum', 'is_admin'])->group(function () {
    Route::post('create/roles',[AdminController::class,'createroles']);
    Route::post('create/newusers',[AdminController::class,'createAccounts']);
    Route::post('create/newusers',[AdminController::class,'createAccounts']);
    Route::post('create/newusers',[AdminController::class,'createAccounts']);


});


//user 
Route::middleware(['auth:sanctum', 'is_user'])->group(function () {
    Route::post('create/roles',[UserController::class,'createpet']);
    Route::post('create/roles',[UserController::class,'createappointment']);
    
    

});

Route::controller(AuthController::class)->group(function () {

Route::post('register','register');
Route::post('login','login');


});
