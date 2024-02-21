<?php
namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class MpesaController extends Controller
{
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
    public function sendMoney($invoiceId,$amount)
    {
        try {
            //code...
            $accessToken = $this->testpay();
            $phone = auth()->user()->phone;
            $timestamp = date('YmdHis');
            $password = '174379' . 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919' .$timestamp;
        $response = Http::withHeaders(
            [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',

            ])->post('https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest', 
            [
        "BusinessShortCode" => "174379",
        "Password" => base64_encode($password),
        "Timestamp" => $timestamp, // Use current timestamp
        "TransactionType" => "CustomerPayBillOnline",
        "Amount" => $amount,
        "PartyA" => $phone,
        "PartyB" => "174379",
        "PhoneNumber" => $phone,
        "CallBackURL" => "https://new-vetio-project.onrender.com/api/callback",
        "AccountReference" => "Test",
        "TransactionDesc" => "Test"
            
        ]);

        if ($response->successful()) {
            $responseData = $response->json();
    
            // Extract necessary data from the response if needed
            $merchantRequestID = $responseData['MerchantRequestID'];
            $checkoutRequestID = $responseData['CheckoutRequestID'];
           
            $payment = Payment::create([
                'user_id' => auth()->id(),
                'invoice_id' => $invoiceId,
                'amount' => $amount,
                'merchant_request_id' => $merchantRequestID,
                'checkout_request_id' => $checkoutRequestID,
                // Add more fields as needed
            ]);

            // Update invoice status to 'paid'
            $invoice = Invoice::findOrFail($invoiceId);
            $invoice->update(['status' => 'paid']);


            return response()->json([
                'success' => true,
                'payment'=> $payment,
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
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                $th->getMessage()
            ],500);
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function handleCallback(Request $request)
{
    // Log the callback data for debugging or auditing
    Log::info('M-Pesa Callback Received:', $request->all());


    
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
