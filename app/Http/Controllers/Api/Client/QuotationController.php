<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\ApiClientRequest\QuotationRequest;
use App\Models\{Client,State,Quotation};
use DB;


class QuotationController extends ApiController
{
   

    public function index()
    {
        try {
            
            $states = State::getAllQuotations($this->_identity->id);

            return $this->_response
            ->successMessage($states);

        } catch (\Exception $e) {

            return $this->_response
            ->errorMessage($e->getMessage());

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

        
            $response=$quotation->items()
                ->createMany($collection);
            
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

            $dataResponse = Quotation::getQuotation($this->_identity->id,$id);

            return $this->_response
            ->successMessage($dataResponse);

        } catch (\Exception $e) {

            return $this->_response
            ->errorMessage();

        }
    }


}