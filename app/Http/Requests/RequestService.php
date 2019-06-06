<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest,
App\Services\ResponseApiService,
Illuminate\Http\Exceptions\HttpResponseException,
Illuminate\Validation\ValidationException,
 Illuminate\Contracts\Validation\Validator,
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


    protected function failedValidation(Validator $validator)
     {
         $errors = (new ValidationException($validator))->errors();

         $errors = $this->parseErrorRest($errors);

         $response = new ResponseApiService();
         $result= $response->errorMessage(trans('requestapi.message_invalid_validator'),
             Response::HTTP_NON_AUTHORITATIVE_INFORMATION, $errors,$this->type);

         throw new HttpResponseException($result);
     }

}
