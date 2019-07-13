<?php namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject as AuthenticatableUserContract;
use Illuminate\Notifications\Notifiable;
use Config;
use DB;
class Client extends Authenticatable implements AuthenticatableUserContract {

    /**
     * Generated
     */
    use EntrustUserTrait;
    use Notifiable;
    protected $table = 'clients';
    protected $connection= 'mysql';
    public $timestamps = true;
    protected $guarded = [];
    protected $fillable = [
        'id',
        'fullname',
        'card',
        'plate',
        'company',
        'apitoken',
        'remember_token',
        'app_id',
        'appversion',
        'tokendevice',
        'typedevice',
        'flagactive',
        'preference',
        'company',
        'document_id',
        'document','latitude','longitude','address'
    ];
    const PREFIX_CACHE= 'clients_';
    const PASSWORD='123456';
    const flagactive=1;
    const TABLE_NAME= 'clients';

    protected $hidden = [
        'password', 'remember_token','apitoken',
        'tokendevice','created_at','updated_at',
        'deleted_at','typedevice','app_id',
        'appversion','gasoline_id','pivot'
    ];


    public function quotations()
    {
        return $this->hasMany(Quotation::class, 'client_id', 'id');
    }

 
    public function states()
    {
        return $this->belongsToMany(State::class,'quotations');
    }

    public function categoryQuotation()
    {
        return $this->hasManyThrough(CategoryQuotation::class,
        Quotation::class,
        'client_id', 'quotation_id', 'id'
    );
    }


    public function app() {
        return $this->belongsTo(App::class, 'app_id', 'id');
    }


    public function apps() {
        return $this->belongsToMany(App::class, 'history_logins', 'client_id', 'app_id');
    }



    public function historyLogins() {
        return $this->morphMany(HistoryLogin::class,'user');
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

   
}
