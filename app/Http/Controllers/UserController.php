<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use JWTAuth;
use JWTAuthException;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    private function getToken($email, $password)
    {
        $token = null;
        //$credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt(['email' => $email, 'password' => $password])) {
                return response()->json([
                    'response' => 'error',
                    'message' => 'Password or email is invalid',
                    'token' => $token
                ]);
            }
        } catch (JWTAuthException $e) {
            return response()->json([
                'response' => 'error',
                'message' => 'Token creation failed',
            ]);
        }
        return $token;
    }
    public function login(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|min:4'
        ]);

        //check for errors
        $errors = $validator->errors();

        //Loop through error
        foreach ($errors->all() as $message) {
            $response = [
                'error' => true, 
                'success' => false,
                'message' => $message
            ];
            return response()->json($response, 200);
        }

        $user = User::where('email', $request->email)->get()->first();
        $checkPasswords = Hash::check($request->password, $user->password);
        if ($user && $checkPasswords) // The passwords match...
        {
            $token = self::getToken($request->email, $request->password);
            $user->auth_token = $token;
            $user->save();

            //response
            $response = [
                'success' => true, 
                'error' => false,
                'message' => 'Logged in successfully',
                'data' => [
                    'id' => $user->id,  
                    'email' => $user->email,
                    'name' => $user->name,
                    'auth_token' => $user->auth_token
                    ]
            ];
            return response()->json($response, 201);

        }
        $response = [
            'error' => true,
            'success' => false, 
            'message' => 'Invalid login credentials'
        ];

        return response()->json($response, 200);
    }



    public function register(Request $request)
    {
        $payload = [
            'password' => \Hash::make($request->password),
            'email' => $request->email,
            'name' => $request->name,
            'auth_token' => ''
        ];

        //validator rules
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:4|max:255',
            'email' => 'required|unique:users',
            'password' => 'required|min:4'
        ]);

        //check for errors
        $errors = $validator->errors();

        //Loop through error
        foreach ($errors->all() as $message) {
            $response = [
                'error' => true,
                'success' => false, 
                'message' => $message
            ];
            return response()->json($response, 200);
        }

        $user = new User($payload);
        if ($user->save()) {

            $token = self::getToken($request->email, $request->password);
            // generate user token

            if (!is_string($token))  return response()->json(['success' => false, 'data' => 'Token generation failed'], 200);

            $user = User::where('email', $request->email)->get()->first();

            $user->auth_token = $token; // update user token

            $user->save();

            $response = [
                'error' => false,
                'success' => true, 
                'message' => 'Registration Successful',
                'data' => $user
            ];
            return response()->json($response, 201);
        } else {
            $response = [
                'error' => true,
                'success' => false, 
                'data' => 'Registration failed'
            ];
            return response()->json($response, 400);
        }
    }
}
