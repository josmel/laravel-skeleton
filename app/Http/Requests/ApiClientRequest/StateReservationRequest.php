<?php

namespace App\Http\Requests\ApiClientRequest;

use App\Http\Requests\RequestService;

class StateReservationRequest extends RequestService
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
       $date=date("Y-m-d");
        if (!is_null($this->request->get('states'))) { 
           
            foreach ($this->request->get('states') as $key => $val) {
                $rules['states.' . $key . '.name'] = 'required|exists:state_reservation,display_name';
            }
        }
        if(!is_null($this->request->get('state'))){
            $rules['state'] = 'required|exists:state_reservation,display_name';
        }

        if(!is_null($this->request->get('date')) && !is_null($this->request->get('hour'))){
            $rule['date'] ='required|date|date_format:"Y-m-d"|after_or_equal:'.$date;
            $rule['hour'] = 'required|date_format:"H:i"'; 
        }

      
        return $rules;
    }

    public function messages()
    {
        return [
            'date.after_or_equal' => 'La fecha debe de ser mayor o igual a la fecha actual'
        ];
    }
}
