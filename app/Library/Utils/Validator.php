<?php namespace App\Library\Utils;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Validator as LaravelValidator;

class Validator extends LaravelValidator {

    public function validateCurrentPassword($attribute, $value, $parameters)
    {
        return Hash::check($value, auth()->guard('api_client')->user()->password);
    }

}