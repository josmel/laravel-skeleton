<?php

namespace App\Services;

use Illuminate\Http\Response;

class ResponseApiService
{
    const STATE_OK= 1;
    const STATE_ERROR= 0;

    protected $_messageok;
    protected $_messageerror;
    protected $_status = Response::HTTP_OK;
    protected $_state = self::STATE_OK;
    protected $_msg= '';
    protected $_data= array();
    protected $_data_error= array();
    protected $_headers= array();

    public function __construct()
    {
        $this->_messageok= trans('responseapi.defaultmessage_ok');
        $this->_messageerror= trans('responseapi.defaultmessage_error');
    }

    public function addHeader($type, $value){
        $headers= $this->_headers;
        $headers[$type]= $value;
        $this->_headers= $headers;
    }

    public function successMessage($data= array(), $msg= null, $status= null,$state=self::STATE_OK){
        $status=(is_null($status))?Response::HTTP_OK:$status;
        $this->setData($state, $msg, $data, [], $status);
        return $this->response();
    }

    public function errorMessage($msg= null, $status= Response::HTTP_INTERNAL_SERVER_ERROR, $dataError= array(), $data= array()){
        $this->setData(self::STATE_ERROR, $msg, $data, $dataError, $status);

        return $this->response();
    }


    public function getDataError($msg= null, $dataError= array(), $data= array()){
        $this->setData(self::STATE_ERROR, $msg, $data, $dataError);

        return $this->getData();
    }

    protected function response(){
        $data= $this->getData();

        $response= response()->json($data, $this->_status);

        foreach ($this->_headers as $key => $value) {
            $response->header($key, $value);
        }

        return $response;
    }

    protected function getData(){
        return [
            "state"=> $this->_state,
            "msg"=> $this->_msg,
            "data"=> $this->_data,
            "data_error"=> $this->_data_error,
        ];
    }

    protected function setData($state, $msg= false, $data=array(), $dataError= array(), $status= false){
        if(!$status)
            $status= ($state == self::STATE_OK)? Response::HTTP_OK : Response::HTTP_INTERNAL_SERVER_ERROR;

        if(!$msg)
            $msg= ($state == self::STATE_OK)? $this->_messageok : $this->_messageerror;

        $this->_state= $state;
        $this->_status= $status;
        $this->_data= $data;
        $this->_data_error= $dataError;
        $this->_msg= $msg;
    }

}