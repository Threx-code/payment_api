<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Passport;

class RegistrationTest extends TestCase
{
       /**
* @group apilogintests
*/    
public function testApiRegistration() {

    Passport::actingAs(
        User::factory()->create(['username' => uniqid()]), ['check-status']
    );
   
    $response = $this->post('/api/register')
        ->assertStatus(422);
}
}
