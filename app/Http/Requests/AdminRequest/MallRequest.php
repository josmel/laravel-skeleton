<?php

namespace App\Http\Requests\AdminRequest;

use App\Http\Requests\RequestService;
use Config;
use Route;

class MallRequest extends RequestService
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
           if($method=='POST'){
               $required= 'required|';
           }

        return [
            'name' => "$required",
            'address' => "$required",
            'contact_name' => "$required",
            'contact_position' => "$required",
            'contact_phone' => "$required",
            'contact_email' => "$required email",
            'flagactive' =>'integer|in:0,1',
        ];

    }
}
