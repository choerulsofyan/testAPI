<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 500,
                'message' => 'Bad Request',
                'error' => $validator->errors()
            ], 401);
        }

        unset($input['confirm_password']);
        $input['password'] = Hash::make($input['password']);
        $input['role'] = 'user';
        $query = User::create($input);

        $response['token'] = $query->createToken('users')->accessToken;
        $response['email'] = $query->email;

        return response()->json($response, 200);
    }

    public function login(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 500,
                'message' => 'Bad Request',
                'data' => $validator->errors()
            ], 401);
        }

        $check_users = User::where('email', '=', $input['email'])->first();

        if (@count($check_users) > 0) {
            $password = $input['password'];

            if (Hash::check($password, $check_users['password'])) {
                $response['token'] = $check_users->createToken('users')->accessToken;
                $response['code'] = 200;
                $response['message'] = 'Login Successfully';
                $response['data'] = $check_users;

                return response()->json($response, 200);
            } else {
                $response['code'] = 401;
                $response['message'] = 'Password not match';

                return response()->json($response, 401);
            }
        } else {
            $response['code'] = 401;
            $response['message'] = 'Email not match';

            return response()->json($response, 401);
        }
    }
}
