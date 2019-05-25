<?php

namespace App\Http\Requests\BrandRequest;

use App\Http\Requests\RequestService;
use Config;
use Route;
class LocalRequest extends RequestService
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
            //'description' => "$required",
            'address' => "$required",
            'mall_id' => 'integer|exists:malls,id',
            'type_local_id' => 'integer|exists:type_local,id',
            'flagactive' =>'integer|in:0,1',
        ];

    }
}
