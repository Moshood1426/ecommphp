<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['min:3', 'max:20', 'required'],
                'email' => ['required', 'email', Rule::unique('users', 'email')],
                'password' => ['required', 'min:6']
            ]);

            if ($validator->fails()) {
                return response()->json(
                    ['statusCode' => '422', 'message' => $validator->messages()],
                    422
                );
            }

            $formFields = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->pasword
            ];

            $formFields['password'] = bcrypt($formFields['password']);

            $user = User::create($formFields);

            auth()->login($user);

            return response()->json(['user' => $user], 201);
        } catch (\Throwable $th) {
            return response()->json(
                ['statusCode' => '422', 'message' => "Something went wrong"],
                422
            );
        }
    }

    public function login()
    {
    }

    public function logout()
    {
    }
}
