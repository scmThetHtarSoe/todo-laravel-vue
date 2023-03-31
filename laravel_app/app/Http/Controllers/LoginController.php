<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * Register api process for user
     *
     * @param  $request
     * @return \Illuminate\Http\Response
     */
    public function processRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ], [
            "name.required" => "Please Enter Your Name!",
            "email.required" => "Please Enter Your Email!",
            "email.email" => "Invalid Email!",
            "email.unique" => "Email has been already taken!",
            "password.required" => "Please Enter Your Password!",
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        } else {
            $name = $request->name;
            $email = $request->email;
            $password = $request->password;
            $user = User::create([
                "name" => $name,
                "email" => $email,
                "password" => Hash::make($password)
            ]);
            return response()->json(['data' => $user, 'token_type' => 'Bearer', 'msg' => 'User Created Successfully!!!'], 200);
        }
    }


    /**
     * Login api process for user
     *
     * @param  $request
     * @return \Illuminate\Http\Response
     */
    public function processLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ], [
            "email.required" => "Please Enter Your Email!",
            "password.required" => "Please Enter Your Password!",
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        } else {
            $loginUser = User::where('email', $request->email)->first();
            if (collect($loginUser)->isNotEmpty()) {
                if (Hash::check($request->password, $loginUser->password)) {
                    $user = $loginUser;
                    $token = $user->createToken('auth_token')->plainTextToken;
                    return response()->json(['data' => $user, 'access_token' => $token, 'token_type' => 'Bearer', 'msg' => 'User Login Successfully!!!'], 200);
                } else {
                    return response()->json(['msg' => 'Incorrect Password!!!'], 200);
                }
            } else {
                return response()->json(['msg' => 'Invalid User!!!'], 200);
            }
        }
    }

    /**
     * Logout api process for user
     *
     * @param  $request
     * @return \Illuminate\Http\Response
     */
    public function processLogout()
    {
        auth()->logout();
        return response()->json(['msg' => 'User logout Successfully!!!'], 200);
    }
}
