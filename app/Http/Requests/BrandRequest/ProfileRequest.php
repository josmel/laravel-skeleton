<?php

namespace App\Http\Requests\BrandRequest;

use App\Http\Requests\RequestService;
use Config;

class ProfileRequest extends RequestService
{

    protected $type = null;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = auth()->guard('brand')->user()->id;
        return [
            'name' => 'min:2,max:255',
            'lastname' => 'min:2,max:255',
            'email' => "email|max:255|unique:admins_brands,email,$id",
            'password' => 'confirmed'
        ];

    }
}
