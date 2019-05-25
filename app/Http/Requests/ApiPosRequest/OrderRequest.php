<?php

namespace App\Http\Requests\ApiPosRequest;

use App\Http\Requests\RequestService;
use Illuminate\Validation\Rule;
class OrderRequest extends RequestService
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
      $local_id=auth()->guard('api_pos')->user()->local_id;
      
        $type_user=$this->input('type_user');
        $user=auth()->guard('api_pos')->user()->local;
        $type_local_id= $user->type_local_id;

        if (!is_null($this->input('coupon_id', null)) && $this->input('coupon_id', null) != "") {
        
         $rule['coupon_id'] = "exists:coupon_local,coupon_id,local_id,".$local_id.",state,2,flagactive,1";
         
        }
        
        $rule['app_client_id'] =($type_user==1)?"required|exists:app_client,id,flagactive,1":
        "required|exists:brand_user,id,brand_id,".$user->brand_id;

        //$rule['code_orden'] = ($type_local_id==1)?'required|min:4|unique:orders,code':
        //'required';

        $rule['code_orden'] = ($type_local_id==1)?'required|min:4':
        'required';

        if($type_local_id==1){
            $rule['flag_delivery'] = 'required|in:0,1';
        }

        $rule['type_user'] = 'required|in:1,2';

        return $rule;
    }
}
