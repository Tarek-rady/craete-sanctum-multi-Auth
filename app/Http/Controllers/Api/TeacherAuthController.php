<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Api\ApiResponseTrait;
use App\Models\OptCode;
use App\Models\Teacher;
use Illuminate\Support\Facades\Log;

class TeacherAuthController extends Controller
{
    use ApiResponseTrait;


    public function register(Request $request  ) {
        $validator = Validator::make($request->all(), [
            'name_ar' => 'required|string|between:2,100',
            'name_en' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'mobile' => 'required|string|min:6|exists:opt_codes,mobile',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }



        $code = OptCode::select('mobile' , 'opt')->where(['mobile' => $request->mobile])->first();

        if($code){

            $user = Teacher::create(array_merge(
                $validator->validated(),
            ));

            $token = $user->createToken('token-name')->plainTextToken;
            $response = [
              'user' => $user ,
              'token' => $token ,
            ];
            return $this->apiResponse($response , 201 , 'user created suessfully');
        } else{
            return $this->apiResponse(null , 404 , 'user Not found ');
        }




    }

    public function login(Request $request)
    {
         // validation
         $validator = Validator::make($request->all(), [
            'mobile' => 'required|exists:opt_codes,mobile',
            'opt' => 'required|exists:opt_codes,opt' ,
        ]);

        if($validator->fails()){
            return $this->apiResponse($validator->errors()->toJson(), 400 , 'unauthintation.');
        }
        $code = OptCode::select('mobile' , 'opt')->where(['mobile' => $request->mobile , 'opt' => $request->opt])->first();
        $user = Teacher::where('mobile' , $request->mobile)->first();

        if ($code){
            $token =  $user->createToken('token')->plainTextToken;
        }else{
            throw ValidationException::withMessages([
                'mobile' => ['The provided credentials are incorrect.'],
            ]);
        }


        $response =[
            'user' => $user ,
            'token' => $token
        ];
        return $this->apiResponse($response , 201 , 'user login sucessfully');


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
      $otp = OptCode::where(['mobile' => $request->mobile , 'opt' => $request->otp])->first();

      if($otp){
          return $this->apiResponse($otp , 200 , 'الكود ورقم التليفون متطابقان');
      }else{
        return $this->apiResponse($otp , 400 , 'Not found');
      }

    }



}
