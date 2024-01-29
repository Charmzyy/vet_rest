<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Room;

use App\Models\User;
use App\Mail\NewUser;
use App\Models\Breed;
use App\Models\Specie;
use App\Models\Appointment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function createroles(Request $request)

    {    try {
        //code...
        $validateData = $request->validate([
            'name'=> 'required',
            'description'=>'required',
         ]);
 
         $role = Role::create([
             'name'=>$validateData['name'],
             'description'=>$validateData['description']
         ]);
         
 
         return response()->json([ 
           'role'=> $role
 
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
                'role_id'=>'required'
                
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
            if(!$appointment){
                return response()->json(['Message'=>'Appointment Not found'],404);
            }
            $validateData = $request->validate([
            'doc_id'=>'required',
            'room' => 'required'
            ]);
            $appointment->doc_id = $validateData['doc_id'];
            $appointment->status = 'confirmed';
            $appointment->save();

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
            return response()->json(['Message'=> 'No Doctors Available']);

        }
        
        return response()->json([
            
                'doctors' => $doctors
            
            
        ],200);


} catch (\Throwable $th) {
    //throw $th;

    return response()->json([
   $th->getMessage()
    ],500);
}
    }

    public function getAvailableRooms() {

        $rooms = Room::where('is_available', '1')->get();
        
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
}
