<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;

trait ValidationTrait
{
    public function validatePostRequest($request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'body' => 'required',
        ]);

        return $validator;
    }
}

