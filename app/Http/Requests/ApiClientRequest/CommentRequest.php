<?php

namespace App\Http\Requests\ApiClientRequest;

use App\Http\Requests\RequestService;

class CommentRequest extends RequestService
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
       
        $id= auth()->guard('api_client')->user()->id;
        $rule['food'] =  'required|numeric|in:1,2,3,4,5';
        $rule['time'] =  'required|numeric|in:1,2,3,4,5';
        $rule['service'] =  'required|numeric|in:1,2,3,4,5';
        $rule['waiter'] =  'required|numeric|in:1,2,3,4,5';
        //$rule['people'] =  'numeric';
        $rule['order_id']='required|exists:orders,id,flagactive,1,ordertable_id,'.$id;
        return $rule;
    }

    public function messages()
    {
        return [
            'date.after_or_equal' => 'La fecha debe de ser mayor o igual a la fecha actual'
        ];
    }
}
