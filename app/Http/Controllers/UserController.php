<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use App\Models\Pet;
use App\Rules\PastDate;
use App\Models\Appointment;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function createpet(Request $request)
    {
        //
        try {
            //code...
            $today = Carbon::now(); 
            $userId = $request->user()->id;
            $validateData = $request->validate([

                'pet_name'=>'required',
                'species_id'=>'required',
                'breed_id' => 'required',
                'dob' => ['required', new PastDate]
                
    
            ]);
            $pet = Pet::create([
                'pet_name'=> $validateData['pet_name'],
                'owner_id' => $userId,
                'species_id'=> $validateData['species_id'],
                'breed_id' => $validateData['breed_id'],
                'dob' => $validateData['dob'],
               
    
            ]);
    
            return response()->json([
                'pet' => $pet,
                'message' => 'appointment created succesfully '
            ]);

        } catch (\Throwable $th) {
            //throw $th;

            return response()->json([
                $th->getMessage()],500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createappointment(Request $request, $id)
    {
        try {
            //code...
            $today = Carbon::now();
            $userId = $request->user()->id;
            $validateData = $request->validate([

                'description'=>'required',
                'book_date'=>'required|date|after_or_equal:'.$today->toDateString(),
                'book_time'=>'required|date_format:H:i',
    
            ]);
            $appointment = Appointment::create([
    
                'description' => $validateData['description'],
                'owner_id' => $userId,
                'book_date'=> $validateData['book_date'],
                'book_time'=> $validateData['book_time'],
                'pet_id' => $id,
    
            ]);
    
            return response()->json([
                'appointment' => $appointment,
                'message' => 'appointment created succesfully '
            ]);

        } catch (\Throwable $th) {
            //throw $th;

            return response()->json([
                $th->getMessage()],500);
        }
       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

   
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
