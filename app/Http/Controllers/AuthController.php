<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    
    public function register(Request $request)
    {
        try {
            //code...
            $validateData = $request->validate
        ([
            'firstname'=>'required',
            'lastname'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|same:confirm_password|min:8',
            'confirm_password'=>'required'


        ]);
        

        $user = User::create ([
            'firstname' => $validateData['firstname'],
            'lastname' => $validateData['lastname'],
            'email' => $validateData['email'],
            'password' =>Hash::make($validateData['password']),
            'confirm_password' => $validateData['confirm_password'],

        ]);

         if(User::count() >= 1) {
            $user->role_id = 2;
            $user->save();
         }
         $token = $user->createToken('token')->plainTextToken;

         $data = [
            $user,
            $token 
         ];

        return response()->json([
            $data
        ],201);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([$th->getMessage()]);
        }
        

    }

    /**
     * Display the specified resource.
     */
    public function login(Request $request)
    {//change error code to give unauthorized 401 error not 500
        try {
            //code...
            $credentials = $request->only(['email', 'password']);
            error_log(json_encode($credentials));
            if (Auth::attempt($credentials)) {
                 $user = auth()->user();
                 $token = $user->createToken('token')->plainTextToken;
                 if(Auth::user()->role_id ==0){
                 
                  $roleArray = ['admin'];
                 }
                 elseif(Auth::user()->role_id==1){
                   
                    $roleArray = ['doctor'];
                 }
                 elseif(Auth::user()->role_id==2){
                   
                    $roleArray = ['user'];
                 }
                 
                 else{
                    return response()->json([
                        "Message"=>"Not Sure which user type you are"
                    ],403);
                 }
                 
                 return response()->json([ 'user' => $user,
                 'role'=>$roleArray,
                 'token' => $token],201);
            }
    return response()->json(['Message'=>'invalid credentails'],401);
    
           
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([$th->getMessage()],500);
        }
       
    
      
    
       
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