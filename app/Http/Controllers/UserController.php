<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use App\Models\Pet;
use App\Models\Invoice;
use App\Rules\PastDate;
use App\Models\Boarding;
use App\Models\Appointment;
use App\Mail\RescheduleMail;
use App\Models\Booking_room;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;
use App\Mail\CancelledAppointment;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
   
    public function createpet(Request $request)
    {
        //
        try {
            //code...
            $today = Carbon::now(); 
            $userId = $request->user()->id;
            $validateData = $request->validate([

                'pet_name'=>'required',
                'species_id'=>'required',
                'breed_id' => 'required',
                'dob' => ['required', 'date', 'before_or_equal:' . $today->toDateString()]

                
    
            ]);
            $pet = Pet::create([
                'pet_name'=> $validateData['pet_name'],
                'owner_id' => $userId,
                'species_id'=> $validateData['species_id'],
                'breed_id' => $validateData['breed_id'],
                'dob' => $validateData['dob'],
               
    
            ]);
    
            return response()->json([
                'pet' => $pet,
                'message' => 'created succesfully '
            ]);

        } catch (\Throwable $th) {
            //throw $th;

            return response()->json([
                $th->getMessage()],500);
        }
    }

    public function createappointment(Request $request, $id)
    {
        try {
            //code...
            $today = Carbon::now();
            $userId = $request->user()->id;
            $validateData = $request->validate([

                'description'=>'required',
                'book_date'=>'required|date|after_or_equal:'.$today->toDateString(),
                'book_time'=>'required|date_format:H:i',
    
            ]);
            $appointment = Appointment::create([
    
                'description' => $validateData['description'],
                'owner_id' => $userId,
                'book_date'=> $validateData['book_date'],
                'book_time'=> $validateData['book_time'],
                'pet_id' => $id,
    
            ]);
            return response()->json([
                'appointment' => $appointment,
                'message' => 'appointment created succesfully '
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                $th->getMessage()],500);
        }
       
    }
    public function book(Request $request,string $id)
    {   try {
        //code...
        $today = Carbon::now();
        $pet = Pet::findOrfail($id);
        
  
        $user = auth()->user()->id;
        $validateData = $request->validate([
            'reservation' =>'required',
            'start'=>'required|date|after_or_equal:'.$today->toDateString(),
            'end'=>'required|after:' .$today->toDateString(),
            'room_id'=>'required',
        ]);

        $room = Booking_room::findOrfail($validateData['room_id']);
        if ($room->status !== 'available') {
            return response()->json(['error' => 'Room is not available for booking.'], 422);
        }
    
        // Create the booking
        $newBook = Boarding::create([
            'reservation' => $validateData['reservation'],
            'start' => $validateData['start'],
            'end' => $validateData['end'],
            'room_id' => $room->id,
            'pet_id' => $pet->id,
            'owner_id' => $user->id,
        ]);
        
        $room->status = 'booked';
        $room->save();
        return response()->json([
            'newbooking', $newBook,
            'message','Created Succesfully'
        ],201);
    } catch (\Throwable $th) {
        
        return response()->json([
            $th->getMessage()],500);
    }}

    public function mybills(){
        try {
            //code...
        $me = auth()->user()->id;
        $appointments = Appointment::where('owner_id',$me)->get();
        $myInvoices = [

        ];
        foreach ($appointments as $appointment) {
            $appointmentId = $appointment->id;
            $pet = $appointment->pet->pet_name;
            $amount = $appointment->myinvoice->amount;
            $dueDate = $appointment->myinvoice->due_date;
            $invoiceId = $appointment->myinvoice->id;
            

            $myInvoices [] = [
            'invoiceId'=>$invoiceId,  
            'appointmentId'=>$appointmentId,
            'pet'=>$pet,
            'amount'=>$amount,
            'dueDate'=>$dueDate
            ];
        }
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                $th->getMessage()
            ],500);
        }  
    }

    public function payBill(string $id){

    $invoice = Invoice::findOrfail($id);
    $amount = $invoice->amount;

    $mpesaController = new MpesaController();
    $response = $mpesaController->sendMoney($id,$amount);
    
    return response()->json([
'response'=> $response
    ]);

    }

    public function invoiceSpecs(string $id)
    {
        $appointment = Appointment::findOrFail($id);
        $serviceDetails = [];
        $serviceCount = $appointment->services->count();
        $services = $appointment->services;
        foreach ($services as $service) {
            $serviceDetails[] = [
                'name' => $service->name,
                'price' => $service->price,
            ];
        }
        return response()->json([
            'details' => $serviceDetails,
            'count' => $serviceCount,
        ]);
    }

    public function rescheduleAppointment(Request $request,$id)
    {
        $appointment = Appointment::findOrfail($id);
        $doctorname = $appointment->myDoctor->firstname;
        if($appointment->doc_id){
            $doctoremail = $appointment->myDoctor->email;

            }
        $data = [
            'book_date' => $appointment->book_date,
                    'book_time' => $appointment->book_time,
                    'pet'=> $appointment->pet->pet_name,
                    'name' => $doctorname

        ];
        Mail::to($doctoremail)->send(new RescheduleMail($data));

        $book_date = $request->input('book_date');
        $book_time= $request->input('book_time');
        $appointment->book_date = $book_date;
        $appointment->book_time = $book_time;
        $appointment->doc_id = null;
        $appointment->save();
        return response()->json([
            'message'=> 'rescheduled',
        ],201);
    }

    public function cancelAppointment($id)
    {   
        $appointment= Appointment::findOrfail($id);
        $doctorname = $appointment->myDoctor->firstname;
        $now = Carbon::now();
        $appointmentDateTime =Carbon::parse($appointment->book_date . ' ' . $appointment->book_time);
        if($now->diffInHours($appointmentDateTime) <= 24)
        {
            return response()->json([
                'Message ' => 'To finsh this you need to pay price for late cancellation '
            ]);
        }
        if($appointment->doc_id){
            $doctoremail = $appointment->myDoctor->email; }
        else {
            $doctoremail = null;
        }
        $data = [
            'book_date' => $appointment->book_date,
                    'book_time' => $appointment->book_time,
                    'pet'=> $appointment->pet->pet_name,
                    'name'=>$doctorname,
        ];
        $appointment->delete();
        if ($doctoremail && $appointment->status === 'confirmed') {
            Mail::to($doctoremail)->send(new CancelledAppointment($data));
        }
        return response()->json([
            'message' => 'appointment cancelled'
        ],201);
    }
}
