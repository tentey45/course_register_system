<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;

class PaymentController extends Controller
{

    public function callback(Request $request)
    {
        $tran_id = $request->input('tran_id');

        if (!$tran_id) {
            return response()->json(["message" => "Missing transaction ID"], 400);
        }

        $payment = \App\Models\Payment::where('transaction_id', $tran_id)->first();

        if (!$payment) {
            return response()->json(["message" => "Payment record not found"], 404);
        }

        if ($request->input('status') == "0") {
            $payment->update(['status' => \App\Models\Payment::STATUS_PAID]);
        } else {
            $payment->update(['status' => \App\Models\Payment::STATUS_FAILED]);
        }

        return response()->json(["message" => "received"]);
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