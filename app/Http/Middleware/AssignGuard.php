<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiResponseTrait;

class AssignGuard
{
     use ApiResponseTrait;
    public function handle(Request $request, Closure $next , $guard = null)
    {

        if($guard != null){
          auth()->shouldUse($guard);
        }
        if($request->user()->user_type == 'users' && $guard != 'users-api'){
            return $this->apiResponse(null , 400 , 'unauthintation');
        }

        if($request->user()->user_type == 'teachers' && $guard != 'teachers-api'){
            return $this->apiResponse(null , 400 , 'unauthintation');
        }

        return $next($request);
    }
}
