<?php

namespace App\Http\Requests\ApiClientRequest;

use App\Http\Requests\RequestService;

class SearchClientRequest extends RequestService
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
            "search"=>"required|min:3"
        ];

    }
}
