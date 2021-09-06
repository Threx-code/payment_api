<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Passport\Client;
use Laravel\Passport\Passport;
use App\Models\User;

class DepositTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_make_deposit()
    {
        $user = User::factory(['username' => uniqid()])->create();
        Passport::actingAs($user);

        $response = $this->json('POST', '/api/deposit', [
                'amount' => '100',
                'send_by' => $user->id,
                'sent_to' => $user->id,
                'amount' => 5000,
                'type' => 'deposit',
                'current_balance' => 5000
            ]);

        $response->assertStatus(200);
    }
}
