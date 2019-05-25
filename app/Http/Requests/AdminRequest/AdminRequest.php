<?php

namespace App\Http\Requests\AdminRequest;

use App\Http\Requests\RequestService;
use Config;
use Route;
class AdminRequest extends RequestService
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
               $id= ",".Route::current()->parameters()['admin']->id;
           }

        return [
            'name' => "$required min:2,max:255",
            'lastname' => "$required min:2,max:255",
            'email' => "$required email|max:255|unique:admins,email$id",
            'password' => "$required confirmed"
        ];

    }
}
