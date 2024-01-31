<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function myPendingAppointments()
    {   try {
        //code...
        $me = auth()->user()->id;
        $pendingAppointments = Appointment::where('doc_id',$me)
                                            ->orderBy('book_date')
                                            ->orderBy('book_time')
                                            ->get();
        $data = [];
        foreach ($pendingAppointments as $pendingAppointment) {

            $data [] = [
                'petname' => $pendingAppointment->pet->pet_name,
                 'owner'=> $pendingAppointment->pet->owner->firstname,
                 'description' => $pendingAppointment->description,
            ];
        }
            return response()->json([
                'appointments' => $data
            ]);
          
        

    } catch (\Throwable $th) {
        //throw $th;
        return response()->json([
            $th->getMessage()
        ]);
    }
        
       
                                            
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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

    /**
     * Show the form for editing the specified resource.
     */
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
