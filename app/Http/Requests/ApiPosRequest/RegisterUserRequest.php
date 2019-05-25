<?php

namespace App\Http\Requests\ApiPosRequest;

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
            'password' =>  "$required confirmed|required_with:current_password",
            'app_id' => "$required|integer|exists:apps,id",
            'appversion' => "$required",
            'tokendevice' =>"$required"
        ];

    }


    public function messages()
    {
        return [
            'current_password' => 'La Contraseña actual es inválida',
            'email' => 'El correo ingresado ya existe.',
        ];
    }
}
