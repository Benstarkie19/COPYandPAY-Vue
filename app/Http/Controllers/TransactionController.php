<?php

namespace App\Http\Controllers;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * 
     *
     * @return \Illuminate\Http\Response
     */
    public function showAll($user_id)
    {
        $transactions = Transaction::all()->where('user_id', $user_id)->toArray();
        return array_reverse($transactions);
    }

    /**
     * 
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transactions = Transaction::find($id)->toArray();
        return $transactions;
    }

    public function storeTransaction($data) {
        return $data[1];
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
}
