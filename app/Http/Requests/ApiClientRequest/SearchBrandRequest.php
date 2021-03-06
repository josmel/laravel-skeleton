<?php

namespace App\Http\Requests\ApiClientRequest;

use App\Http\Requests\RequestService;

class SearchBrandRequest extends RequestService
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
        return [
            "product_id"=>"required|exists:products,id",
            "search"=>"required|min:3"
        ];

    }
}
