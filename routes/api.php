<?php

use App\Http\Controllers\Api\TransactionController;
use Illuminate\Support\Facades\Route;



Route::post('/mock-response', [TransactionController::class,'mock_response']);

Route::post('/store-transaction', [TransactionController::class,'store_transaction']);

Route::post('/transaction-callback', [TransactionController::class,'transaction_callback']);