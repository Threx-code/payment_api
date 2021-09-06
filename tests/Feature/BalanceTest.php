<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Passport\Client;
use Laravel\Passport\Passport;
use App\Models\User;

class BalanceTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_that_user_can_check_balance()
    {

        $user = User::factory(['username' => uniqid(), 'password' => 'password'])->create();
        Passport::actingAs($user);

        $body = [
            'username' => $user->email,
            'password' => bcrypt('password')
        ];
       

        $this->withExceptionHandling();
        $response = $this->get('/balance', $body)
        ->assertStatus(404);
    }
}
