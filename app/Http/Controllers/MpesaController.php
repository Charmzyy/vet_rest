<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
    public function sendMoney()
    {
        // $user = auth()->user();
        // $userNumber = $user->phone;
        $accessToken = $this->testpay();
        $response = Http::withHeaders(
            [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',

            ])->post('https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest', 
            [
        "BusinessShortCode" => "174379",
        "Password" => "MTc0Mzc5YmZiMjc5ZjlhYTliZGJjZjE1OGU5N2RkNzFhNDY3Y2QyZTBjODkzMDU5YjEwZjc4ZTZiNzJhZGExZWQyYzkxOTIwMTYwMjE2MTY1NjI3",
        "Timestamp" => date('YmdHis'), // Use current timestamp
        "TransactionType" => "CustomerPayBillOnline",
        "Amount" => "1",
        "PartyA" => '254712849736',
        "PartyB" => "174379",
        "PhoneNumber" => '254712849736',
        "CallBackURL" => " https://shy-turkeys-pay.loca.lt/api/callback",
        "AccountReference" => "Test",
        "TransactionDesc" => "Test"
            
        ]);

        if ($response->successful()) {
            $responseData = $response->json();
    
            // Extract necessary data from the response if needed
            $merchantRequestID = $responseData['MerchantRequestID'];
            $checkoutRequestID = $responseData['CheckoutRequestID'];
            // You can extract more data as needed
    
            // Return the response or any necessary data
            return response()->json([
                'success' => true,
                'message' => 'Payment request initiated successfully',
                'merchantRequestID' => $merchantRequestID,
                'checkoutRequestID' => $checkoutRequestID,
                // Include more data if needed
            ]);
        } else {
            // Handle the error response
            $errorResponse = $response->json();
    
            return response()->json([
                'success' => false,
                'error' => $errorResponse,
            ], $response->status());
        }
    }

    /**
     * Display the specified resource.
     */
    public function handleCallback(Request $request)
{
    // Log the callback data for debugging or auditing
    Log::info('M-Pesa Callback Received:', $request->all());

    // Process the callback data and update your application's state
    // For example, you might update the transaction status in your database

    // Respond with a success message to acknowledge receipt of the callback
    return response()->json(['message' => 'Callback received'], 200);
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
