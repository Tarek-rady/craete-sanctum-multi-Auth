<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Api\ApiResponseTrait;
use App\Models\OptCode;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    use ApiResponseTrait;

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'mobile' => 'required|string|min:6|exists:opt_codes,mobile',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }


        $code = OptCode::select('mobile' , 'opt')->where(['mobile' => $request->mobile])->first();

        if($code){
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
        }else{
            return $this->apiResponse(null , 400 , 'user Not found');
        }


    }

    public function login(Request $request)
    {
         // validation
         $validator = Validator::make($request->all(), [
            'mobile' => 'required|exists:opt_codes,mobile',
            'opt' => 'required|exists:opt_codes,opt' ,        ]);

        if($validator->fails()){
            return $this->apiResponse($validator->errors()->toJson(), 400 , 'unauthintation.');
        }
        $code = OptCode::select('mobile' , 'opt')->where(['mobile' => $request->mobile , 'opt' => $request->opt])->first();
        $user = User::where('mobile' , $request->mobile)->first();

        if($code){
            $token =  $user->createToken('token')->plainTextToken;

            $response =[
                'user' => $user ,
                'token' => $token ,
                'code' => $code
            ];

            return $this->apiResponse($response , 201 , 'user login sucessfully');
        }else{
            throw ValidationException::withMessages([
                'mobile' => ['The provided credentials are incorrect.'],
            ]);
        }



    }

    public function logout(Request $request) {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'user has logout successfully']);
    }

    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|string|min:6|unique:opt_codes,mobile',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }


        $otp = rand(1000 , 9999);
        Log::info("otp = ".$otp);
        $code = OptCode::create([
            'mobile' => $request->mobile ,
            'opt' => $otp
        ]);

        return $this->apiResponse($code , 201 , 'تم ارسال الكود بنجاح');
    }

    public function checkOtp(Request $request)
    {
      $otp = OptCode::where(['mobile' => $request->mobile , 'opt' => $request->opt])->first();

      if($otp){
          return $this->apiResponse($otp , 200 , 'الكود ورقم التليفون متطابقان');
      }else{
        return $this->apiResponse(null , 400 , 'Not found');
      }

    }

}

