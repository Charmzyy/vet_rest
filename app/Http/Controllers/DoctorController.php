<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\MedicalRecordFile;
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
                'id' =>$pendingAppointment->id,
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
    public function create(Request $request,$id)

    {
        try {
            //code...
            $appointment = Appointment::findOrfail($id);
            $validateData = $request->validate([
            'title' => 'required',
            'description'=> 'required',
            'images.*'=> 'image|mimes:jpeg,jpg,png,bmp|max:4080'
            ]);
    
            $medical_record = MedicalRecord::create([
                'appointment_id' => $appointment->id,
                'title'=> $validateData['title'],
                'description' => $validateData['description']
            ]);
    
            
            if($request->hasFile('images')){
                foreach ($request->file('images') as $image) {
    
                    $filepath = $image->store('medical_records_files','public');
    
                    $medical_record->myfiles()->create([
                        'medical_record_id' => $medical_record->id,
                        'file_path' => $filepath,

                    ]);
                       
                  
                    # code...
                
                }
    
                return response()->json([
                    'medical_record' => $medical_record,
                    
                    'Message' => 'Created Successfully',
                ],201);
            }

        } catch (\Throwable $th) {
            //throw $th;

            return response()->json([
                $th->getMessage()
            ],500);
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
