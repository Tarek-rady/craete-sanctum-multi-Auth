<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Api\ApiResponseTrait;

class AuthController extends Controller
{
    use ApiResponseTrait;
    public function login(Request $request)
    {
         // validation
         $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6',
        ]);

        if($validator->fails()){
            return $this->apiResponse($validator->errors()->toJson(), 400 , 'unauthintation.');
        }

        $user = User::where('email' , $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token =  $user->createToken('token')->plainTextToken;

        $response =[
            'user' => $user ,
            'token' => $token
        ];
        return $this->apiResponse($response , 201 , 'user login sucessfully');


    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        $token = $user->createToken('token-name')->plainTextToken;
        $response = [
          'user' => $user ,
          'token' => $token
        ];

        return $this->apiResponse($response , 201 , 'user created suessfully');

    }

    public function logout(Request $request) {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'user has logout successfully']);
    }

// 5|ykhee1hXegOoniFYqmdHlteUKHBnWm8xALdcGiws
}
