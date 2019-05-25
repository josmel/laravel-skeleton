<?php

namespace App\Http\Requests\ApiClientRequest;

use App\Http\Requests\RequestService;
use Route;
class RegisterUserRequest extends RequestService
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
        $method = Route::current()->methods[0];
        $required='';
        $id='';
        if($method=='POST'){
            $required= 'required|';
        }else{
            $id= ",".auth()->guard('api_client')->user()->id;
        }
        return [
            'fullname' =>"$required min:2,max:255",
            'email' =>  "$required email|max:255|unique:app_client,email$id",
            'current_password' => 'min:6|current_password',
            'password' =>  "$required min:6|confirmed|required_with:current_password",
            'app_id' => "$required|integer|exists:apps,id",
            'phone' => "$required |regex:/^[0-9]+$/|size:9|unique:app_client,code$id",
            'appversion' => "$required",
            'tokendevice' =>"$required"
        ];

    }


    public function messages()
    {
        return [
            'fullname.required' => 'El nombre es obligatorio',
            'current_password' => 'La contraseña actual es inválida',
            'phone.required' => 'El teléfono es obligatorio',
            'phone.size' => 'El teléfono debe contener solo 9 caracteres ',
            'phone.unique' => 'El teléfono ya esta en uso',
            'email.unique' => 'El correo ingresado ya esta en uso',
            'email.email' => 'El correo tiene un valor inválido',
            'phone.regex' => 'El teléfono solo debe contener números',
            'password.min' => 'La contraseña debe contener mínimo 6 caracteres',
            'password.confirmed' => 'La confirmación de contraseñas no coincide',
        ];
    }
}
