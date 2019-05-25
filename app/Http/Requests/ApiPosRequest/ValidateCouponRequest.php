<?php

namespace App\Http\Requests\ApiPosRequest;

use App\Http\Requests\RequestService;

class ValidateCouponRequest extends RequestService
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


    public function rules()
    {
       return [
           'client_id'=>'required|exists:app_client,id,flagactive,1',
           'coupon'=>'required'

       ];
    }
}
