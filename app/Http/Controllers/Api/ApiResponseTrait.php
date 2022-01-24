<?php

namespace App\Http\Controllers\Api;

trait ApiResponseTrait
{
   public function apiResponse($data ,  $status  , $msg){

      $array = [
        'data' => $data ,
        'status' => $status ,
        'message' => $msg
      ];

      return response($array);


   }
}

