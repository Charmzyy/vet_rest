<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

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
