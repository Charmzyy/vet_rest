<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MpesaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['message' => 'hello world']);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function testpay(){

        try {
            //code...
        $consumerKey = 'yro3SxvPWDGhjcpnp4qFY9vnDKE7VUZD8VaGtVRKQ5Tow312';
        $consumerSecret = 'AvvigsVw9eG8AeswEklaWOkQLU4DvHfZzFX6L7qgGCRiG1lUBixVs5qMXLC8hDrZ';
    
        // Encode Consumer Key and Consumer Secret for Basic Authentication
        $credentials = base64_encode($consumerKey . ':' . $consumerSecret);
    
    
    
    
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $credentials,
        ])->get('https://sandbox.safaricom.co.ke/oauth/v1/generate', [
            'grant_type' => 'client_credentials',
        ]);
    
            if ($response->successful()) {
                $responseData = $response->json();
        
                // Extract the access token and expiry time
                $accessToken = $responseData['access_token'];
                $expiresIn = $responseData['expires_in'];
        
                // Optionally, you can return the access token or handle it further
                return $accessToken;
            } else {
                // Handle the error
                return response()->json(['error' => 'Failed to authorize tokens'], $response->status());
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
