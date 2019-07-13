<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\ApiClientRequest\SearchProductRequest;
use App\Models\Brand;
use Datatables;

class BrandController extends ApiController
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
            
            $product = $request->input('product_id', "");
           
            $response = Datatables::eloquent(Brand::getBrand($search, $product));
            
            return $response
            ->make(true);

        } catch (\Exception $e) {

            return $this->_response
            ->errorMessage();

        }
    }
  

}
