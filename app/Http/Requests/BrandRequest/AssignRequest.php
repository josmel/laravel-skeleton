<?php

namespace App\Http\Requests\BrandRequest;

use App\Http\Requests\RequestService;
use Config;
use Route;
class AssignRequest extends RequestService
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
        return [
            'client_id' => 'required',
            'coupon_id' => 'required|integer|exists:coupons,id'
        ];

    }
}
