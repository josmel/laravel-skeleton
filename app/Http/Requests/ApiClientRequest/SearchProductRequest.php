<?php

namespace App\Http\Requests\ApiClientRequest;

use App\Http\Requests\RequestService;
use Illuminate\Http\Request;

class SearchProductRequest extends RequestService
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


    public function rules(Request $request)
    {

        $requestResponse=[];

        $requestResponse["search"]="required|min:3";
        if($request->route()->getName()=="brand.index"){
            $requestResponse["product_id"]="required|integer|exists:products,id";
        }

        return $requestResponse;

    }
}
