<?php

namespace App\Http\Requests\ApiRequest;

use App\Http\Requests\RequestService;
use Config;
use App\Models\Gasonet\Eess;
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
            'type'=>'required|in:1,2,3,4',
            'title'=>'required|min:4',
            'quotation.*.quantity' => 'required|integer',
           // 'quotation.*.description' => 'required',
            'quotation.*.product' => 'min:2',
            'quotation.*.brand' => 'min:2',
            'quotation.*.product_id' => 'exists:products,id',
            'quotation.*.brand_id' => 'exists:brands,id',
        ];
    }
}