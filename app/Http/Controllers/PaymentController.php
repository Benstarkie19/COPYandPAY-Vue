<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\TransactionController;

class PaymentController extends Controller
{
    public function purchaseDetails(Request $request) {

        $message = $request->amount . $request->reference . $request->currency;
        $success = true;

        try {
            $message = $this->getCheckoutDetails($request);
        } catch (\Throwable $th) {
            $message = $th;
            $success = false;
        }

        // response
        $response = [
            'success' => $success,
            'message' => $message,
        ];
        
        return response()->json($response);
    }

    

    

    private function storeTransaction($user_id, $jsonData) {
        DB::table('transactions')->insert([
            'transaction_id' => $jsonData['id'],
            'payment_type' => $jsonData['paymentType'],
            'payment_brand' => $jsonData['paymentBrand'],
            'amount' => $jsonData['amount'],
            'currency' => $jsonData['currency'],
            'merchant_transaction_id' => $jsonData['merchantTransactionId'],
            'user_id' => $user_id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }

    

    private function getPaymentStatus(Request $request) {
        $url = "https://test.oppwa.com/v1/checkouts/" . $request->id . "/payment";
        $url .= "?entityId=8ac7a4ca759cd78501759dd759ad02df";
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                       'Authorization:Bearer OGFjN2E0Y2E3NTljZDc4NTAxNzU5ZGQ3NThhYjAyZGR8NTNybThiSmpxWQ=='));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if(curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        return $responseData;
    }

    public function processPayment(Request $request) {
        
        $success = true;    

        try {
            $responseData = $this->getPaymentStatus($request);
            $decode = json_decode($responseData, true);
        } catch (\Throwable $th) {
            return $th;
        }

        // response
        $response = [
            'success' => $success,
            'message' => $responseData,
        ];


        if (isset($decode['id']) && isset($decode['paymentType'])) {
            $this->storeTransaction($request->user_id, $decode);
            return view('app', compact('decode'));
        } else {
            return view('app');
        }
    }

    private function getCheckoutDetails(Request $request) {
        $url = "https://test.oppwa.com/v1/checkouts";
        $data = "entityId=8ac7a4ca759cd78501759dd759ad02df" .
                    "&amount=" . $request->amount .
                    "&merchantTransactionId=" . $request->reference .
                    "&currency=" . $request->currency . 
                    "&paymentType=DB";
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                       'Authorization:Bearer OGFjN2E0Y2E3NTljZDc4NTAxNzU5ZGQ3NThhYjAyZGR8NTNybThiSmpxWQ=='));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if(curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        return $responseData;
    }

}
