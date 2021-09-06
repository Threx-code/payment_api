<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Passport\Client;
use Laravel\Passport\Passport;
use App\Models\User;

class LoginTest extends TestCase
{
   /**
* @group apilogintests
* 
*/

    public function test_user_can_login()
    {
        $user = User::factory(['username' => uniqid(), 'password' => 'password'])->create();
        Passport::actingAs($user);

        $body = [
            'username' => $user->email,
            'password' => bcrypt('password')
        ];
        $this->post('/api/login',$body)
            ->assertStatus(422);
            
    }

   
}
