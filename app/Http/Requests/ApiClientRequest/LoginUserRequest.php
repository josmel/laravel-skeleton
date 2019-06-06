<?php

namespace App\Http\Requests\ApiClientRequest;

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
            'email' => 'required|email',
            'password' => 'required|min:6',
            'app_id' =>'required|integer|in:1,2',
            'appversion' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'password.required' => 'El Contraseña es obligatoria',
            'email.required' => 'El corre es obligatorio',
        ];
    }

}
