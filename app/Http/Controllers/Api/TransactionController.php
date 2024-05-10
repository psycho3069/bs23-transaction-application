<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function mock_response(){

        $rand = rand(1,9);

        // accept most of the time but fail sometimes

        if($rand > 2){
            $data['other_data'] = [];
            $data['status'] = 'accepted';

            return response()->json($data, 202,
            [
                'x-mock-response-name' => 'accepted',
                'x-mock-response-code' => '202',
            ]);
        } else {
            $data['other_data'] = [];
            $data['status'] = 'failed';
            
            return response()->json($data, 400,
            [
                'x-mock-response-name' => 'failed',
                'x-mock-response-code' => '400',
            ]);
        }

    }

    public function store_transaction(StoreTransactionRequest $request){
        $payment_data = $request->validated();

        // get status from mock-response and store accordingly for both success or failed
        $raw_response = Http::post(env('APP_URL').'/api/mock-response',$payment_data);
        $response = $raw_response->json();
        $status_code = $raw_response->status();
        $status_code_prefix = str_split($status_code);
        
        if($response['status'] == 'accepted' && $status_code_prefix[0] == '2'){
            // successful or accepted payment
            $payment_data['status'] = 'accepted';
        }

        if($response['status'] == 'failed' && $status_code_prefix[0] == '4'){
            // failed payment
            $payment_data['status'] = 'failed';
        }

        $payment_data['transaction_id'] = Str::ulid()->toBase32();

        $transaction = Transaction::query()->create($payment_data);

        return response()->json($transaction,200,[
            'Cache-Control' => 'no-store'
        ]);

    }
}
