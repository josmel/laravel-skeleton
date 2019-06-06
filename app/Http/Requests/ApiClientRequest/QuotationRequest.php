<?php

namespace App\Http\Requests\ApiClientRequest;

use App\Http\Requests\RequestService;
use Config;

class QuotationRequest extends RequestService
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    { 
       
        return [
            'date'=>'required|date|date_format:Y-m-d',
            'address'=>'required',
            'specification'=>'required|min:4',
            'type_payment'=>'required|in:1,2',
            'type_address'=>'required|in:1,2,3',
            'title'=>'required|min:4',
                'quotation.*.quantity' => 'required|integer',
                'quotation.*.product' => 'required_without:quotation.*.product_id',
                'quotation.*.brand' =>   'required_without:quotation.*.brand_id',
                'quotation.*.product_id' => 'required_without:quotation.*.product',
                'quotation.*.brand_id' =>   'required_without:quotation.*.brand'
        ];
    }

    
}