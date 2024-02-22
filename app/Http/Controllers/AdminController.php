<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Room;

use App\Models\User;
use App\Mail\NewUser;
use App\Models\Breed;
use App\Models\Specie;
use App\Models\Appointment;
use App\Models\Booking_room;
use App\Models\Department;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function createdept(Request $request)

    {    try {
        //code...
        $validateData = $request->validate([
            'name'=> 'required',
            'description'=>'required',
         ]);
 
         $department = Department::create([
             'name'=>$validateData['name'],
             'description'=>$validateData['description']
         ]);
         
 
         return response()->json([ 
           'role'=> $department
 
         ]);


    } catch (\Throwable $th) {

    return response()->json([
        $th->getMessage()
    ]);

        //throw $th;
    }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createAccounts(Request $request) {

        try {
            //code...

            $password = Str::random(8);
            $validateData = $request->validate( 
                [
                'firstname'=>'required',
                'lastname'=>'required',
                'email'=>'required|email|unique:users',
                'dept_id'=>'required'
                
                ] 
    
            
                );
                $newUser = User::create([
    
                    'firstname' => $validateData['firstname'],
                    'lastname' => $validateData['lastname'],
                    'email' => $validateData['email'],
                    'role_id'=>$validateData['role_id'],
                    'password'=>$password,
                    'confirm_password'=>$password
                     
    
                ]);

                $data = [
                    'firstname' => $newUser->firstname,
                    'email' => $newUser->email,
                    'password'=> $password
    
                ];

                $newUser->role_id =  $validateData['role_id'];
                $newUser->is_available  = true;
                $newUser->save();
                Mail::to($newUser->email)->send(new NewUser($data));
                return response()->json([$data]);

        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([$th->getMessage()],500);
        }
       



            

    }
   

    /**
     * Store a newly created resource in storage.
     */
    public function createspecies(Request $request)
    {
        try {
            //code...
            $validateData = $request->validate( 
                [
                'name'=>'required|unique:species',
                
                ] );
                $specie = Specie::create(['name' => $validateData['name'], ]);
    
                return response()->json([
                    'specie'=>$specie,
                    'message' => 'created Succesully',],201);

        } catch (\Throwable $th) {
            //throw $th;

            return response()->json([$th->getMessage()],500);
        }

    }

    /**
     * Display the specified resource.
     */
    public function createbreeds(Request $request)
    {
        try {
            //code...
            $validateData = $request->validate( 
                [
                'breed_name'=>'required|unique:breeds',
                'species_id'=>'required'
                
                ] );
                $breed = Breed::create(['breed_name' => $validateData['breed_name'],
            'species_id' => $validateData['species_id'] ]);
    
                return response()->json([
                    'breed'=>$breed,
                    'message' => 'created Succesully',],201);

        } catch (\Throwable $th) {
            //throw $th;

            return response()->json([$th->getMessage()],500);
        }
    }

    public function createRooms(Request $request) {

       try {
        //code...
        $validateData = $request->validate([
            'number' => 'required',
    
           ]);
           $room = Room::create([
            'number' => $validateData['number']
           ]);
    
           return response()->json([
            'room' => $room,
            'messsage'=> 'room created succesfully'
           ],201);
    
       } catch (\Throwable $th) {
        //throw $th;
        return response()->json([
            $th->getMessage()
        ],500);
       }
       
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function assigndoctor(Request $request, $id)
    {
        //assign doctor
        try {
            //code...
            $appointment = Appointment::findOrfail($id);
           

            $validateData = $request->validate([
                'doc_id'=>'required',
                'room_number' => 'required'
                ]);

            $doctor = User::where('role_id','1')->find($validateData['doc_id']);
            $room =Room::find($validateData['room_number']);

            if(!$doctor || !$room){
                 return response()->json([
                    'Message' =>  'Doctor and Room not available'
                 ]);

            }
            $exisitingDoctorAppointments = Appointment::where('doc_id', $doctor->id)
                                           ->where('id', '!=', $id)
                                           ->where('book_date', $appointment->book_date)
                                           ->where('book_time',$appointment->book_time)
                                           ->exists();

            if($exisitingDoctorAppointments) {
                return response()->json([
                    'Message'=> 'Doctor already has an appointment'
                ]);
            }
            
            $exisitingRoomAppointments = Appointment::where('room', $room->id)
                                           ->where('id', '!=', $id)
                                           ->where('book_date', $appointment->book_date)
                                           ->where('book_time',$appointment->book_time)
                                           ->exists();

            if($exisitingRoomAppointments) {
                return response()->json([
                    'Message'=> 'Room has  already been booked '
                ]);
            }

            $appointment->doc_id = $doctor->id;
            $appointment->room_number  =$room->id;
            $appointment->status = 'confirmed';
            $appointment->save();

            $room->update(['is_available' => false]);
            $room->save();

            return response()->json([
                'appointment' => $appointment,
                'message' => 'Appointment assigned successfully',
            ], 201);
            return response()->json([
                'appointment' => $appointment,
                'message' =>'appointment assigned successully'
            ],201);

        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([$th->getMessage()],500);
        }
    }

    public function getNew() {
           $appointments = Appointment::where('status','pending')->get();
           if($appointments->IsEmpty()){
            return response()->json([ 'Message' => 'No Any new Appointments']);
           }
           $appointmentsData = [];
           foreach($appointments as $appointment) {

            $ownerName = $appointment->pet->owner->firstname;
            $appointmentsData[] = [
                'appointment' => $appointment,
                'owner' => $ownerName
            ];

            return response()->json([
                'appointments' => $appointmentsData
            ]);
           }

           return response()->json([
               'appointment' => $appointmentsData,
               
           ]);
    }

    public function alldoctors(){  

try {
     $doctors = User::where('role_id','1')->get();
        if($doctors->isEmpty()){
            return response()->json(['Message'=> 'No Doctors Available']); }
         return response()->json([
              'doctors' => $doctors
        ],200);
} catch (\Throwable $th) {
    return response()->json([
   $th->getMessage()
    ],500);
}
    }

    public function getAvailableRooms() {

        $rooms = Room::where('is_available', '1')->get();
        return response()->json([
        'rooms' => $rooms,
        'message' => 'All Available Rooms'
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrfail($id);
        if($user->isEmpty()){
            return response()->json([
                'Message'=> 'No user found'
            ]);
        }
        $user->delete();
    }

    public function confirmedAppointments() {
        try {
            //code...
            $confirmedAppointments = Appointment::where('status', 'confirmed');
            if($confirmedAppointments->isEmpty()){
                return response()->json([
                    'Message'=> 'No confirmed Appointment'
                ]);
            }

        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                $th->getMessage()
            ]);
        }
       
    }

public function make_rooms(Request $request){

    try {
        //code...
        $validateData = $request->validate([
            'name' => 'required',
    
           ]);
           $room = Booking_room::create([
            'name' => $validateData['name']
           ]);
    
           return response()->json([
            'room' => $room,
            'messsage'=> 'room created succesfully'
           ],201);
    
       } catch (\Throwable $th) {
        //throw $th;
        return response()->json([
            $th->getMessage()
        ],500);
       }
       
}


}
