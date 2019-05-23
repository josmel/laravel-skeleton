<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\StateTarj;
use Illuminate\Http\Request;
use App\Models\BaseModel;
use Illuminate\Support\Facades\Password as Pass;
use App\Mail\Register;
use App\Models\User,
    Hash,
    DB,
    Auth,
    JWTAuth,
    Cache,
    Utils,
    Mail,
    Password,
    Lang,
    Log,
    Config;

/**
 * Class UserService
 * @package App\Services
 */
class UserService
{

    protected  $broker;
    protected  $guard;

    public function __construct($broker,$guard)
    {
        $this->broker=$broker;
        $this->guard=$guard;
    }



    public function loginUser($data,$client=null, $role)
    {
        DB::beginTransaction();

        try {
            $token= false;
            $appService= new AppService;
            $modelApp= $appService->verifyVersion($data['app_id'], $data['appversion']);

            $newGuard = auth()->guard($this->guard);

            if(!is_null($client)){
                if (!$token=$newGuard->login($client))
                    return [ResponseApiService::STATE_ERROR, trans('responseapi.auth.failed')];
            }else{
                $dataRequest=$data;
                unset($dataRequest['app_id']);  unset($dataRequest['appversion']);
                if (!$token=$newGuard->attempt($dataRequest))
                    return [ResponseApiService::STATE_ERROR, trans('responseapi.auth.failed')];
            }

            $modelUSer = $newGuard->getUser();

            if ($modelUSer->statejarj->id!=StateTarj::state_active)
                return [ResponseApiService::STATE_ERROR, trans('responseapi.auth.not-state')];

            if (!$modelUSer->hasRole($role))
                return [ResponseApiService::STATE_ERROR, trans('responseapi.auth.not-authorized')];

            if ($modelUSer->apitoken) {
                $this->invalidateToken( $modelUSer->apitoken);
            }

            $modelUSer->update([
                'apitoken' => $token,
                'app_id' => $modelApp->id,
                'appversion' => $data['appversion'],
                'typedevice' => $modelApp->typedevice,
            ]);
            $this->registerHistoryLogin($modelUSer);

        } catch (\Exception $e) {
            DB::rollBack();
            if($token !== false) {
                $this->invalidateToken($token);
            }
            throw new \Exception($e->getMessage());
        }

        DB::commit();

        return $modelUSer;
    }


    public function logout($AppUser) {
        try {
            $AppUser->apitoken = null;
            $AppUser->save();
            return auth()->guard($this->guard)->logout();
        } catch (\Exception $ex) {
            return FALSE;
        }
    }





    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

       return trans($response);
    }


    public function broker()
    {
        return Pass::broker($this->broker);
    }



    protected function registerHistoryLogin($modelUser)
    {
        $ip= Utils::getUserIp();
        $agent= Utils::getUserAgent();
        $modelUser->historyLogins()->create([
            'app_id'=> $modelUser->app_id,
            'typedevice'=> $modelUser->typedevice,
            'agent'=> $agent,
            'ip'=> $ip,
            'appversion'=> $modelUser->appversion,
        ]);

    }

    public function invalidateToken($token) {
        try {
            return auth()->guard($this->guard)->setToken($token)
                ->invalidate();
        } catch (\Exception $ex) {
            return FALSE;
        }
    }


    //////////////////////////////////////////////////////////////////////////////////////////

    public function find($id)
    {
        $modelUser= User::find($id);

        if(!$modelUser)
            throw new \Exception("Usuario inválido");

        return $modelUser;
    }

    public function emailRegister(Client $modelUser,$password)
    {

        Mail::to($modelUser->email)->send(new KryptoniteFound);
        $langRegister= trans('mail.user.register');
        try {
            Mail::send('auth.emails.register', ['user' => $modelUser,'password'=>$password], function ($message) use ($modelUser, $langRegister) {
                $message->to($modelUser->email, $modelUser->fullname)->subject($langRegister['title']);
            });
        } catch (\Exception $e){
            Log::error('Error en el envío de email de registro de usuario: ' . $e->getMessage());
        }
    }

    protected function verifyUniqueEmail($email, $role, $id= 0)
    {
        $minutes = Config::get('cache.ttl');
        $user = Cache::remember(Client::PREFIX_CACHE . $email . $role, $minutes, function() use ($email, $role, $id) {
            $query= Client::where(Client::TABLE_NAME . ".username", $email)
                ->join(RoleUser::TABLE_NAME, RoleUser::TABLE_NAME . '.user_id', '=', Client::TABLE_NAME . '.id')
                ->where(RoleUser::TABLE_NAME . '.role_id', $role)
                ->select(Client::TABLE_NAME . '.*');

            if(!empty($id))
                $query->where(User::TABLE_NAME . '.id', '!=', $id);

            return $query->first();
        });

        if($user)

            //return $this->_response->errorMessage('Unauthorized action',403);
            throw new \Exception("la tarjeta ingresado ya existe");
    }
    public function registerUser($data, $role)
    {
        DB::beginTransaction();

        try{
            $appService= new AppService;
            $modelApp= $appService->verifyVersion($data['app_id'], $data['appversion']);

            $modelRole= $this->getRoleByName($role,true);
            $this->verifyUniqueEmail($data['card'], $modelRole->id);

            $modelBroker= new Broker();
            $objBroker= $modelBroker->getMainBroker();

            $data['password']= Hash::make($data['password']);
            $data['typedevice']= $modelApp->typedevice;
            $data['broker_id']= $objBroker->id;

            $modelUser= new User;
            $modelUser->fill($data);
            $modelUser->save();

            Auth::login($modelUser);
            $token = JWTAuth::fromUser($modelUser);
            $modelUser->apitoken= $token;
            $modelUser->save();

            $minutes = Config::get('cache.ttl');
            Cache::put(User::PREFIX_CACHE . $modelUser->email, $modelUser, $minutes);

            $this->assignUserRole($modelUser->id, $modelRole->id);
            $this->registerHistoryLogin($modelUser);

            $this->emailRegister($modelUser,$data['password']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }

        DB::commit();

        return $modelUser;
    }

    public function processUserByRole($data, $role= false)
    {
        DB::beginTransaction();

        try{
            $passs=null;
            $modelRole= $this->getRoleByName($role,true);

            $idUser= (isset($data['id']))? $data['id'] : 0;

            if(isset($data['email']))
                $this->verifyUniqueEmail($data['email'], $modelRole->id, $idUser);

            if(isset($data['password']) && !empty($data['password'])) {
                $passs=$data['password'];
                $data['password'] = Hash::make($data['password']);
            }else{
                unset($data['password']);
            }

            if(isset($data['id'])){
                $modelUser= Client::find($data['id']);

                if(!$modelUser)
                    throw new \Exception("Usuario inválido");
            }else{
                $modelUser= new Client();
            }

            $modelUser->fill($data);
            $modelUser->save();

            if(!isset($data['id'])) {
                $this->assignUserRole($modelUser->id, $modelRole->id);
                $this->emailRegister($modelUser,$passs);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }

        DB::commit();

        return $modelUser;
    }

    public function getRoleByName($role, $fail= false)
    {
        $minutes = Config::get('cache.ttl');
        $role = Cache::remember(Role::PREFIX_CACHE . $role, $minutes, function() use ($role, $fail) {
            $role= Role::where('name', $role)->first();

            if($fail && !$role)
                throw new \Exception("El rol no existe");

            return $role;
        });

        return $role;
    }

    public function assignUserRole($idUser, $role, $flagName= false)
    {
        if($flagName) {
            $modelRole = $this->getRoleByName($role, true);
            $role= $modelRole->id;
        }

        $dataRoleUser= [
            'user_id'=> $idUser,
            'role_id'=> $role,
        ];

        RoleUser::create($dataRoleUser);
    }

}