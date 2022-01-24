<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Api\ApiResponseTrait;
use App\Models\Teacher;

class TeacherAuthController extends Controller
{
    use ApiResponseTrait;
    public function login(Request $request)
    {
         // validation
         $validator = Validator::make($request->all(), [
            'mobile' => 'required|exists:teachers,mobile',
        ]);

        if($validator->fails()){
            return $this->apiResponse($validator->errors()->toJson(), 400 , 'unauthintation.');
        }

        $user = Teacher::where('mobile' , $request->mobile)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'mobile' => ['The provided credentials are incorrect.'],
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
            'name_ar' => 'required|string|between:2,100',
            'name_en' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'mobile' => 'required|string|min:6',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = Teacher::create(array_merge(
            $validator->validated(),
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
}
