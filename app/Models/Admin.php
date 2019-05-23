<?php namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword as ResetPasswordNotification;
use Tymon\JWTAuth\Contracts\JWTSubject as AuthenticatableUserContract;
class Admin extends  Authenticatable implements AuthenticatableUserContract
{
    use Notifiable;
   use EntrustUserTrait;
    /**
     * Generated
     */
    
    protected $table = 'admins';
    public $timestamps = true;
    protected $guarded = [];
    protected $fillable = [
        'id',
        'email',
        'password',
        'name',
        'lastname',
        'flagactive',
        'apitoken'
    ];
//    protected $casts = [
//        'flagactive' => 'boolean',
//    ];
    protected $hidden = [
        'password', 'remember_token','updated_at','deleted_at','created_at','apitoken'
    ];
    public function edits() {
        return $this->hasMany(Edit::class, 'admin_id', 'id');
    }

//    public function roleuser() {
//        return $this->morphMany(RoleUser::class, 'user');
//    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey();
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    }

    static function getAllAdmins($admin_id =null){
        //BaseModel::STATE_FLAGACTIVE
        $query= self::whereNotNull('flagactive');
        return (is_null($admin_id))?$query:$query->where('id','!=',$admin_id);
    }



}
