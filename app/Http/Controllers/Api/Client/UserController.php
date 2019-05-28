<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\ApiClientRequest\LoginUserRequest,
    App\Models\Role,
    App\Services\UserService,
    App\Http\Requests\ApiClientRequest\PasswordUserRequest,
    App\Models\ClientPlus;
use App\Library\Utils\Utils;
use App\Models\AppClient;
use App\Http\Requests\ApiClientRequest\RegisterUserRequest;
use Storage;
use Config;
use App\Services\Notification;
use DB;
use Hash;

class UserController extends ApiController
{
    protected $_UserService;
    protected $broker = 'clients';
    protected $guard = 'client';
    protected $path = 'users';

    function __construct()
    { 
        $this->_UserService = new UserService($this->broker, $this->guard);
        parent::__construct();
    }

    public function index()
    {
        $client = $this->_identity;
        $client->image = $this->getImage($client->image);
        return $this->_response->successMessage($client);
    }

    public function postLogin(LoginUserRequest $request)
    {
         try {

            $data = $request->all();

            $modelUser = $this->_UserService
                        ->loginUser($data, null, [Role::USER_APP_CLIENT]);

            if (isset($modelUser[0])) {

                return $this->_response
                        ->successMessage(null, $modelUser[1], null, $modelUser[0]);

            }

            unset($modelUser->roles);

            $this->_response->addHeader('_token', $modelUser->apitoken);

            $modelUser->image = $this->getImage($modelUser->image);

            return $this->_response
            ->successMessage($modelUser);


     } catch (\Exception $e) {

            return $this->_response
            ->errorMessage($e->getMessage());
        }
    }


    public function getLogout()
    {
        $this->_UserService->logout($this->_identity);
        return $this->_response->successMessage();
    }

    public function postPassword(PasswordUserRequest $request)
    {
        try {
            
            $response = $this->_UserService->sendResetLinkEmail($request);
            return $this->_response
            ->successMessage(null, $response);

        } catch (\Exception $e) {

            return $this->_response
            ->errorMessage($e->getMessage());

        }
    }


    public function store(RegisterUserRequest $request)
    {
        try {
            $data = $request->all();
            $modelUser = $this->_UserService->registerUser($data, Role::USER_APP);
            if (isset($modelUser[0])) {
                return $this->_response->successMessage(null, $modelUser[1], null, $modelUser[0]);
            }
            $this->_response->addHeader('_token', $modelUser->apitoken);
            $modelUserAll=AppClient::find($modelUser->id);
            $modelUserAll->image = $this->getImage($modelUserAll->image);
            return $this->_response->successMessage($modelUserAll);
        } catch (\Exception $e) {
            return $this->_response->errorMessage($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Admin $admin
     * @return \Illuminate\Http\Response
     */
    public function update(RegisterUserRequest $request)
    {
        DB::beginTransaction();
        try {
            $request = $request->all();
            $input = Utils::clearInputs($request);
            /*if (isset($input['image'])) {
                $LibraryUtils = new Utils();
                $input['image'] = $LibraryUtils->createImageToString($input['image'], $this->path,[ env('WITH_ENV', '100'), env('HEIGH_ENV', '100')]);
                (!is_null($this->_identity->image)) ? Storage::delete($this->path . '/' . $this->_identity->image) : "";
            }*/
            if (isset($input['password']) && !empty($input['password'])) {
                $input['password'] = Hash::make($input['password']);
            }
            $this->_identity->update($input);
            
            DB::commit();
            return $this->_response
                ->successMessage();

        } catch (\Exception $ex) {

            DB::rollBack();
            return $this->_response
                    ->errorMessage();
        }
    }

    public function getImage($image)
    {
        return (is_null($image)) ? null : \Storage::url('users/' . $image);
    }

}