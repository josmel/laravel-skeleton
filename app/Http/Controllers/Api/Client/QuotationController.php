<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\ApiClientRequest\QuotationRequest;
use App\Models\Client;
use DB;


class QuotationController extends ApiController
{
   

    public function index()
    {
        try {
            
            $response = Client::find(1)
                        ->with('quotations.products')
                        ->first();

            return $this->_response
            ->successMessage($response->quotations);

        } catch (\Exception $e) {

            return $this->_response
            ->errorMessage();

        }
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuotationRequest $request)
    {
        try {

            DB::beginTransaction(); 

            $collection=$request->input('quotation');

            $dataQuotation=$request->all();


            unset($dataQuotation['quotation']);

            $quotation = $this->_identity->quotations()
            ->create($dataQuotation);

            foreach($collection as $k=>$y){

                $grouped =  [
                    $y['category_id']=> ['description'=>$y['description'],
                                            'quantity'=>$y['quantity'] ]
                ];

                $response=$quotation->products()
                ->attach($grouped);

            }
            
            DB::commit();
            return $this->_response
            ->successMessage($response);

        } catch (\Exception $e) {

            return $this->_response
            ->errorMessage();
            DB::rollBack(); 
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            

            $response = Client::find(1);

           $responsx= $response->quotations()
                    ->where('quotations.id',$id)
                    ->with('products')
                    ->get();

            return $this->_response
            ->successMessage($responsx[0]);


        } catch (\Exception $e) {

            return $this->_response
            ->errorMessage();

        }
    }


}