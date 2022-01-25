<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TeacherAuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['api' , 'checklang' ,'auth:sanctum' ] ] , function(){
        //categories
    Route::group(['prefix' => 'categories'] , function(){
       Route::post('/' , [CategoryController::class , 'index']);
       Route::post('/show' , [CategoryController::class , 'show']);
       Route::post('/store' , [CategoryController::class , 'store']);
       Route::post('/update/{id}' , [CategoryController::class , 'update']);
       Route::post('/destroy/{id}' , [CategoryController::class , 'destroy']);
       Route::post('/changeStatus/{id}' , [CategoryController::class , 'changeStatus']);
    });

    Route::group(['prefix' => 'users' , 'middleware' => 'auth.guard:users-api'] , function(){
        Route::post('profile' , function(){
        return 'only authatcition ol user';
        });
    });


    Route::post('admin/logout' , [AuthController::class , 'logout']);
});

// Auth Users
Route::group(['prefix' => 'admin'] , function(){
    Route::post('/login' , [AuthController::class , 'login']);
    Route::post('/register' , [AuthController::class , 'register']);
    Route::post('/sendOtp' , [AuthController::class , 'sendOtp']);
    Route::post('/checkOtp' , [AuthController::class , 'checkOtp']);
});

// Auth Teacher
Route::group(['prefix' => 'teacher'] , function(){
    Route::post('/login' , [TeacherAuthController::class , 'login']);
    Route::post('/register' , [TeacherAuthController::class , 'register']);
    Route::post('/sendOtp' , [TeacherAuthController::class , 'sendOtp']);
    Route::post('/checkOtp' , [TeacherAuthController::class , 'checkOtp']);
});



