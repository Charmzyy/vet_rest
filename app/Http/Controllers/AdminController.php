<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;

use App\Mail\NewUser;
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
       


        //
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
        $validateData = $request->validate( 
            [
            'firstname'=>'required',
            'lastname'=>'required',
            'email'=>'required|email|unique:users',
            'role_id'=>'required'
            
            ] 

        
            );
            $specie = ::create([

                'firstname' => $validateData['firstname'],
                
                 

            ]);
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
