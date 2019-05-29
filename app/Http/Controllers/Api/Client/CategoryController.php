<?php

namespace App\Http\Controllers\Api;


use App\Http\Requests\ApiRequest\PreferenceRequest;
use App\Models\Client;
use App\Models\Category;

class CategoryController extends ApiController
{


    public function index()
    {
        try {
            $response = Category::all();
            return $this->_response
            
            ->successMessage($response);

        } catch (\Exception $e) {

            return $this->_response
            ->errorMessage();
            
        }
    }
    public function store(PreferenceRequest $request)
    {
        try {
            $client = $this->_identity;
            $client->flagactive = Client::flagactive;
            $client->preference = $request->preference;
            $client->gasoline_id = $request->gasoline_id;
            $client->save();
            return $this->_response->successMessage(null);
        } catch (\Exception $e) {
            return $this->_response->errorMessage();
        }
    }

    public function update(PreferenceRequest $request)
    {
        try {
            $data=$request->all();
            $this->_identity->update($data);
            return $this->_response->successMessage(null);
        } catch (\Exception $e) {
            return $this->_response->errorMessage();
        }
    }

}
