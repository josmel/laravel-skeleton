<?php

namespace App\Http\Requests\ApiClientRequest;

use App\Http\Requests\RequestService;

class ReservationRequest extends RequestService
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
        $date=date("Y-m-d");
        $rule['local_id'] = "exists:locales,id,flagactive,1";
        $rule['date'] ='required|date|date_format:"Y-m-d"|after_or_equal:'.$date;
        $rule['hour'] = 'required|date_format:"H:i"'; 
        $rule['people'] =  'required|numeric';
        
        return $rule;
    }

    public function messages()
    {
        return [
            'date.after_or_equal' => 'La fecha debe de ser mayor o igual a la fecha actual'
        ];
    }
}
