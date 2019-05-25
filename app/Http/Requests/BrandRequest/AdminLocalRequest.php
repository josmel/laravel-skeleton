<?php

namespace App\Http\Requests\BrandRequest;

use App\Http\Requests\RequestService;
use Config;
use Route;
class AdminLocalRequest extends RequestService
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
        $brand_id=auth()->guard('brand')->user()->brand_id;
        $required='';
        $id='';
           if($method=='POST'){
               $required= 'required|';
           }else{
               $id= ",".Route::current()->parameters()['admin_local']->id;
           }
        return [
            'name' => "$required min:2,max:255",
            'lastname' => "$required min:2,max:255",
            'email' => "$required email|max:255|unique:admins_brands,email$id",
            'local_id' => "$required exists:locales,id,brand_id,$brand_id",
            'password' => "$required confirmed",
            'phone' => "$required min:7,max:11"
        ];

    }
}
