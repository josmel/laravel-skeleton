<?php

namespace App\Http\Requests\ApiClientRequest;

use App\Http\Requests\RequestService;

class CouponRequest extends RequestService
{

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

     return [
         'mall_id'=>'exists:malls,id,flagactive,1'
     ];

    }
}
