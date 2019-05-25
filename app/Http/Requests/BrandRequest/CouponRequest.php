<?php

namespace App\Http\Requests\BrandRequest;

use App\Http\Requests\RequestService;
use App\Models\Role;
use Config;
use Route;

class CouponRequest extends RequestService
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
        $date=date("Y-m-d");
        $new=Route::getCurrentRoute()->getAction()['middleware'];
        $guard = is_array($new)?$new[0]:$new;



        $user=auth()->guard($guard)->user();
        $role=Role::getRole($user)->name;
        $method = Route::current()->methods[0];
        $required='';
           if($method=='POST'){
               $required= 'required|';
           }

        $rules= [
            'total_user' => "$required integer",
            'description' => "$required",
            'name' => "$required",
            'image' => "$required",
            'start_date' => $required.' date|date_format:"Y-m-d"|after_or_equal:'.$date,
            'start_end' => $required.' date|date_format:"Y-m-d"|after_or_equal:start_date',
            'flagactive' =>'integer|in:0,1'
        ];

        if (!is_null($this->request->get('locales'))) {
            foreach ($this->request->get('locales') as $key => $val) {
                $rules['locales.' . $key . '.id'] ="required|exists:locales,id".($guard=='admin')?:",brand_id,$guard->brand_id";
                $rules['locales.' . $key . '.total'] = 'required|numeric';
            }
        }
//
//        $rules['total']=($role!=Role::USER_LOCAL)?'required_if:locales,|numeric':
//            'required_unless:flagactive,in:0,1';
        return $rules;
    }
}
