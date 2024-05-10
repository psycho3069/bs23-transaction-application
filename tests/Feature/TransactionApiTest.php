<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionApiTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_store_transaction(): void
    {
        $response = $this->post('/api/store-transaction',[
            'user_id' => 1,
            'amount' => 20,
        ]);

        $response->assertStatus(201);
    }

    public function test_update_transaction(): void
    {
        $response = $this->post('/api/update-transaction',[
            'transaction_id' => '01HXH384TMNE5VFKB57DSGF4TN', // put valid ulid from db
            'status' => 'accepted', // either failed or accepted
        ]);

        $response->assertStatus(200);
    }
}
