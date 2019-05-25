<?php

namespace App\Http\Requests\ApiPosRequest;

use App\Http\Requests\RequestService;
use Illuminate\Validation\Rule;
class BrandUserRequest extends RequestService
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


    public function rules()
    {
        $brand_id= auth()->guard('api_pos')->user()->local->brand_id;

        $rule['fullname'] = "required|min:2,max:255";

        if(is_null($this->input('phone'))){
            $rule['email']    = [ 'required',
            "email",
            "max:255",
            Rule::unique('brand_user')->where(function ($query) use ( $brand_id) {
                $query->where('brand_id',  $brand_id);
            })];
        }
        if(is_null($this->input('email'))){
            $rule['phone']    = ['required',
            'regex:/^[0-9]+$/',
            'size:9',Rule::unique('brand_user')->where(function ($query) use ( $brand_id) {
                $query->where('brand_id',  $brand_id);
            })];
        }
        if(!is_null($this->input('email')) && !is_null($this->input('phone'))){


            $rule['email']    = [ 'required',
            "email",
            "max:255",
            Rule::unique('brand_user')->where(function ($query) use ( $brand_id) {
                $query->where('brand_id',  $brand_id);
            })];


            $rule['phone']    = ['required',
            'regex:/^[0-9]+$/',
            'size:9',Rule::unique('brand_user')->where(function ($query) use ( $brand_id) {
                $query->where('brand_id',  $brand_id);
            })];




        }
       

  

        return $rule;
    }

    public function messages()
    {
        return [
            'fullname.required' => 'El nombre es obligatorio',
            'phone.required' => 'El teléfono es obligatorio',
            'phone.size' => 'El teléfono debe contener solo 9 caracteres ',
            'phone.unique' => 'El teléfono ya esta en uso',
            'email.unique' => 'El correo ingresado ya esta en uso',
            'email.email' => 'El correo tiene un valor inválido'
        ];
    }
}
