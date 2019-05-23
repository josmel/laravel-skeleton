<?php namespace App\Models;

use  Illuminate\Database\Eloquent\Model;
class App extends Model {

    /**
     * Generated
     */
    protected $table = 'apps';
    public $timestamps = true;
    protected $guarded = [];
    protected $fillable = [
        'id',
        'name',
        'typedevice',
        'rule',
        'app_version_id'
    ];
    const PREFIX_CACHE = 'apps_';
    const TABLE_NAME = 'apps';
    const DEVICE_ANDROID = 2;
    const DEVICE_ANDROIDNAME = 'Android';
    const DEVICE_IOS = 3;
    const DEVICE_IOSNAME = 'IOs';
    const DEFAULT_OPERATOR = '==';

    public function clients() {
        return $this->belongsToMany(\App\Models\Client::class, 'history_logins', 'app_id', 'client_id');
    }

    public function appVersions() {
        return $this->hasMany(\App\Models\AppVersion::class, 'app_id', 'id');
    }

    public function clientsTwo() {
        return $this->hasMany(\App\Models\Client::class, 'app_id', 'id');
    }

    public function historyLogins() {
        return $this->hasMany(\App\Models\HistoryLogin::class, 'app_id', 'id');
    }

    public function getAppById($idApp) {
        $query = $this->where(self::TABLE_NAME . '.id', $idApp)
            ->join(AppVersion::TABLE_NAME, AppVersion::TABLE_NAME . '.app_id', '=', self::TABLE_NAME . '.id')
            ->select(self::TABLE_NAME . '.*', AppVersion::TABLE_NAME . '.name as appversion');

        return $query->first();
    }

    public function getDeviceAttribute() {
        $dataDevice = [
            self::DEVICE_ANDROID => self::DEVICE_ANDROIDNAME,
            self::DEVICE_IOS => self::DEVICE_IOSNAME,
        ];

        if (!isset($dataDevice[$this->typedevice]))
            throw new \Exception("Tipo de dispositivo inválido");

        return $dataDevice[$this->typedevice];
    }

    public static function getDeviceAttributeAll() {
        return[
            self::DEVICE_ANDROID => self::DEVICE_ANDROIDNAME,
            self::DEVICE_IOS => self::DEVICE_IOSNAME,
        ];
    }

    public static function getSelectDevice() {
        return [
            "" => "Seleccione el tipo de dispositivo",
            self::DEVICE_ANDROID => self::DEVICE_ANDROIDNAME,
            self::DEVICE_IOS => self::DEVICE_IOSNAME,
        ];
    }

    public static function getSelectRule() {
        return [
            "" => "Seleccione el operador",
            ">" => ">",
            "<" => "<",
            ">=" => ">=",
            "<=" => "<=",
            "==" => "==",
        ];
    }

}
