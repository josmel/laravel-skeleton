<?php

namespace App\Http\Requests\ApiClientRequest;

use App\Http\Requests\RequestService;

class StateOrderRequest extends RequestService
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
       $rules = [];

        if (!is_null($this->request->get('states'))) {
            foreach ($this->request->get('states') as $key => $val) {
                $rules['states.' . $key . '.name'] = 'required|exists:state_order,display_name';
            }
        }
        if(!is_null($this->request->get('state'))){
            $rules['state'] = 'required|exists:state_order,display_name';
        }
        
        return $rules;
    }
}
