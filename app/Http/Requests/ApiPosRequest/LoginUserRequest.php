<?php

namespace App\Http\Requests\ApiPosRequest;

use App\Http\Requests\RequestService;

class LoginUserRequest extends RequestService
{
    protected  $type=null;
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
        return [
            'username' => 'required|min:6',
            'app_id' =>'integer|in:1,2',
            'appversion' => 'required',
        ];
    }


    public function messages()
    {
        return [
            'username.required' => 'El código es  obligatorio',
            'username.min' => 'El código no contiene el mínimo de caracteres requerido'
        ];
    }


}
