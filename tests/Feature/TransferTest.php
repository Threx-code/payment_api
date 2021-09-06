<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Passport\Client;
use Laravel\Passport\Passport;
use App\Models\User;

class TransferTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_make_transfer()
    {
        $user = User::factory(['username' => uniqid()])->create();
        Passport::actingAs($user);

        $response = $this->json('POST', '/api/transfer', [
                'send_by' => $user->id,
                'sent_to' => 2,
                'amount' => 1000,
                'type' => 'credit',
                'current_balance' => 1000
            ]);

        /*this shows that the API does does not allow unauthenticated access*/
        $response->assertStatus(422);
    }
}


