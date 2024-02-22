<?php

use Illuminate\Http\Request;
use App\Models\MedicalRecord;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\MpesaController;

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


Route::get('testpay', [MpesaController::class, 'testpay']);
Route::get('hello', [MpesaController::class, 'index']);




//admin



Route::middleware(['auth:sanctum', 'is_admin'])->group(function () {
    Route::post('create/departments',[AdminController::class,'createdept']);
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
    Route::post('change/{id}/weight',[DoctorController::class,'changeWeight']);
    
    

});

//user 
Route::middleware(['auth:sanctum', 'is_user'])->group(function () {
    Route::post('create/pet',[UserController::class,'createpet']);
    Route::post('create/{id}/appointment',[UserController::class,'createappointment']);
    Route::put('reshedule/{id}/appointment',[UserController::class,'rescheduleAppointment']);
    Route::delete('close/{id}/appointment',[UserController::class,'cancelAppointment']);
    Route::post('send', [MpesaController::class, 'sendMoney']);
    Route::post('callback', [MpesaController::class, 'handleCallback']);
    Route::get('appointment/{id}/invoice',[UserController::class,'invoiceSpecs']);
    Route::post('pay/{id}/bill',[UserController::class,'payBill']);
    Route::get('appointments/mybills',[UserController::class,'mybills']);
    Route::post('pet{id}/bookboarding',[UserController::class,'book']);
    
    
    
    
    
    

});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);





