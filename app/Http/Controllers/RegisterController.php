<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;

class RegisterController extends Controller
{   
    /**
     * [register the register method allows creation of new user into the application if all the required parameters are met]
     * @param  RegistrationRequest $request [This class handles the sanitizations of users input to help prevent sql injections. ]
     * * @param  LoginRequest $request [LoginRequest class sanitizes the user input to make sure the required parameters are not empty]
     * @param $request->validated() [this checked if all required parameters are validated or not]
     * @param $request->name, $request->email,  $request->username, $request->password [these are required parameters for user creation]
     * @param $user [this contains arrays of authenticated user data]
     * @param $token [this contains the token for authenticated users to be able to access parts of the application that require authentication]
     * @return [type] [if the creation is successful, a success resonpse with code 201 is return alongside the newly created data]
     */
    public function register(RegistrationRequest $request)
    {
        if($request->validated()){
            try {
            
                $user = User::create([
                    'name' => $request->name,
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => Hash::make($request->password)
                ]);

                return response()->json([
                    'status' => 201,
                    'success' => true,
                    'data' => [
                        'unique_id' => $user['id'],
                        'name' => $user['name'],
                        'username' => $user['username'],
                        'email' => $user['username'],
                        'created' => $user['created_at']->format('d M, Y h:i A') //diffForHumans(),
                    ],
                ]);
            }
            catch (Exception $e) {
                return response()->json(['message' => $e->getMessage()]);
            }
            
        } 
        
    }
}
