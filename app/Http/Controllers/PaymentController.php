<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;

class PaymentController extends Controller
{

    public function callback(Request $request)
    {

        $data = $request->all();


        // Get transaction ID
        $tran_id = $request->input('tran_id');

if(!$tran_id){
    return response()->json([
        "message"=>"Missing transaction ID"
    ],400);
}


        // Find student registration
        $registration = Registration::where(
            'transaction_id',
            $tran_id
        )->first();



        if(!$registration){
            return response()->json([
                "message"=>"Registration not found"
            ],404);
        }



        /*
        status:
        0 = success
        other = failed
        */

        if($data['status']=="0"){

            $registration->update([
                'payment_status'=>'paid'
            ]);

        }else{

            $registration->update([
                'payment_status'=>'failed'
            ]);

        }



        return response()->json([
            "message"=>"received"
        ]);
    }
    public function checkout($registrationId)
{
    $registration = Registration::with('course')
        ->findOrFail($registrationId);


    return redirect(
        $registration->course->payment_link
    );
}

}