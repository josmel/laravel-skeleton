<?php

namespace App\Http\Requests\BrandRequest;

use App\Http\Requests\RequestService;
use Config;
use Route;
class UserAppRequest extends RequestService
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

        $method = Route::current()->methods[0];
        $required='';
        $id='';
           if($method=='POST'){
               $required= 'required|';
           }else{
               $id= ",".Route::current()->parameters()['user']->id;
           }

        return [
            'name' => "$required min:2,max:255",
//            'lastname' => "$required min:2,max:255",
//            'local_id' => "$required integer|exists:locales,id",
//            'email' => "$required email|max:255|unique:admins,email$id",
//            'password' => "$required confirmed"
        ];

    }
}
