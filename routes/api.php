<?php

use Illuminate\Http\Request;
use App\Models\MedicalRecord;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorController;

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
    Route::post('create/species',[AdminController::class,'createspecies']);
    Route::post('create/breeds',[AdminController::class,'createbreeds']);
    Route::post('create/room',[AdminController::class,'createRooms']);
    Route::put('assign/{id}/doctor',[AdminController::class,'assigndoctor']);
    Route::get('new/appointments',[AdminController::class,'getNew']);
    Route::get('all/doctors',[AdminController::class,'alldoctors']);
    Route::get('confirmed/appointments',[AdminController::class,'confirmedAppointments']);
    


});

Route::middleware(['auth:sanctum', 'is_doctor'])->group(function () {
    Route::get('mypending/appointments',[DoctorController::class,'myPendingAppointments']);
    Route::post('create/{id}/medicalrecord',[DoctorController::class,'create']);
    Route::get('getmedicals',[DoctorController::class,'showMedicalRecords']);
    Route::post('create/{id}/medicalfile',[DoctorController::class,'createMedicalFiles']);
    Route::get('show/{id}/medicals',[DoctorController::class,'showMedicals']);
    Route::post('close/{id}/appointment',[DoctorController::class,'closeappointment']);
    
    

});

//user 
Route::middleware(['auth:sanctum', 'is_user'])->group(function () {
    Route::post('create/pet',[UserController::class,'createpet']);
    Route::post('create/{id}/appointment',[UserController::class,'createappointment']);
    
    
    

});

Route::controller(AuthController::class)->group(function () {

Route::post('register','register');
Route::post('login','login');


});
