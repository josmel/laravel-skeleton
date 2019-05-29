<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\ApiClientRequest\SearchProductRequest;
use App\Models\Product;
use Datatables;

class ProductController extends ApiController
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SearchProductRequest $request)
    {
        try {
            $search = $request->input('search', "");
            $response = Datatables::eloquent(Product::getProduct($search));
            
            return $response
            ->make(true);

        } catch (\Exception $e) {

            return $this->_response
            ->errorMessage();

        }
    }
  

}
