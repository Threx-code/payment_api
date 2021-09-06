<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;


class LoginController extends Controller
{

    /**
     * [login this method allows users to be able to login to access authenticated part of the application]
     * @param  LoginRequest $request [LoginRequest class sanitizes the user input to make sure the required parameters are not empty]
     * @param $request->validated() [this checked if all required parameters are validated or not]
     * @param $request->email_username, $request->password [this allows users to be able to login using either email/username and password]
     * @param $user [this contains arrays of authenticated user data]
     * @param $token [this contains the token for authenticated users to be able to access parts of the application that require authentication]
     * @return [type]                [json response containing the array data of either successful login or unsuccessful login]
     */
    public function login(LoginRequest $request)
    {
        if($request->validated()){
            try {
                if(
                    (Auth::attempt(['email' => $request->email_username, 'password' => $request->password])) ||
                    (Auth::attempt(['username' => $request->email_username, 'password' => $request->password]))
                ){
                    $user = Auth::user();
                    $token = $user->createToken('token')->accessToken;

                    return response()->json([
                        'status' => 'success',
                        'success' => true,
                        'token' => $token,
                        'data' => [
                        'unique_id' => $user['id'],
                        'name' => $user['name'],
                        'username' => $user['username'],
                        'email' => $user['username'],
                        'created' => $user['created_at']->format('d M, Y h:i A') //diffForHumans(),
                        ],

                    ]);
                }
                else{
                    return response()->json([
                        'status' => 'failed',
                        'success' => false,
                        'message' => "Sorry Invalid Credentials"
                    ]);
                }
            }
            catch (Exception $e) {
                return response()->json(['message' => $e->getMessage()]);
            }
            
        } 
    }
}
