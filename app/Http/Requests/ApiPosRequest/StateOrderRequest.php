<?php

namespace App\Http\Requests\ApiPosRequest;

use App\Http\Requests\RequestService;
use Config;
use Route;
class StateOrderRequest extends RequestService
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
            'state' => "in:create,delete,active"
        ];

    }
}
