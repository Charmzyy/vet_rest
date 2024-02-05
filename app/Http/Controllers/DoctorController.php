<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\MedicalRecordFile;
use App\Models\Pet;
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
    { //create medical report for an appointment 
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
    

                return response()->json([
                    'medical_record' => $medical_record,
                    
                    'Message' => 'Created Successfully',
                ],201);
            }

         catch (\Throwable $th) {
            //throw $th;

            return response()->json([
                $th->getMessage()
            ],500);
        }
       

}



     public function showMedicalRecords()
     {
         try {
             $medicalRecords = MedicalRecord::all();
             $data = [];
     
             foreach ($medicalRecords as $medicalRecord) {
                 $medicalFiles = [];
     
                 // Loop through medical files associated with the current medical record
                 foreach ($medicalRecord->myfiles as $file) {
                     $medicalFiles[] = $file->file_path;
                 }
     
                 // Collect medical record data along with associated files
                 $data[] = [
                     'id' => $medicalRecord->id,
                     'title' => $medicalRecord->title,
                     'description' => $medicalRecord->description,
                     'medical_files' => $medicalFiles
                 ];
             }
     
             return response()->json([
                 'medical_records' => $data
             ]);
         } catch (\Throwable $th) {
             return response()->json(['error' => $th->getMessage()], 500);
         }
     }
    
    // }

    /**
     * Show the form for editing the specified resource.
     * 
     * 
     TODO:implement past jobs
     
     

     */
    public function closeappointment(Request $request, $id){
        //close appointment 
        $appointment = Appointment::findOrfail($id);
        $appointment->status = 'closed';
        $appointment->save();
        return response()->json([
            'Message' => 'appointment closed successfully'
        ],201);
    }
    public function createMedicalFiles(Request $request, $id)
    { //createmedical files images for each report
        $medicalRecord = MedicalRecord::find($id);
        $medicalRecordId = $medicalRecord->id;
        $validatedData = $request->validate([
            'file_path' => 'image|required|mimes:jpeg,jpg,png,bmp,gif'
        ]);

        $filePath = $request->file('file_path')->store('public/uploads');
                $medicalFile = MedicalRecordFile::create([
                    'medical_record_id' => $medicalRecordId,
                    'file_path' => $filePath
                ]);
        return response()->json([
            'medical_files' => $medicalFile,
            'message' => 'Created successfully'
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function showMedicals($id)
    {
        try {
            //code...
            $medicalRecord =  MedicalRecord::find($id)->get();
            $medicalRecordId = $medicalRecord->id;
            $medicalRecordTitle = $medicalRecord->title;
            $description = $medicalRecord->description;
            
            // $medicalFiles = [];
            // foreach ($medicalRecord->myfiles as $file) {
            //     $medicalFiles [] = $file->file_path;
            // }
    
    
            return response()->json([
                'id' => $medicalRecordId,
                'title'=>$medicalRecordTitle,
                'description' => $description
                // 'medicalfiles' => $medicalFiles
            ]);

        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' => $th->getMessage()], 500);
        }
       
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
