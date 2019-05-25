<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest,
    App\Services\ResponseApiService,
    Illuminate\Http\Response;

 class RequestService extends FormRequest {

    protected  $type;

    private function parseErrorRest($errors) {
        $resultError = array();
        foreach ($errors as $key => $value) {
            $resultError[] = array('element' => $key,
                'msg' => $value[0]);
        }

        return $resultError;
    }




    public function response(array $errors) {
        $errors = $this->parseErrorRest($errors);
        $response = new ResponseApiService();
        return $response->errorMessage(trans('requestapi.message_invalid_validator'), Response::HTTP_NON_AUTHORITATIVE_INFORMATION, $errors,$this->type);
    }

}
