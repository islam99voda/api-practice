<?php

namespace App\Http\Controllers\Api;

trait ApiResponseTrait
{
   public function apiResponse($data = null, $message = null, $status = 200) // Set default status to 200
   {
       $array = [
           'data' => $data,
           'message' => $message,
           'status' => $status,
       ];

       return response()->json($array, $status); // Ensure $status is passed as integer
    }
}

