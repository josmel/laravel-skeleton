<?php namespace App\Models;

use  Illuminate\Database\Eloquent\Model;
class HistoryLogin extends Model {

    /**
     * Generated
     */
    
    protected $table = 'history_logins';
    public $timestamps = true;
    protected $guarded = [];
    protected $fillable = [
        'id',
        'client_id',
        'app_id',
        'appversion',
        'typedevice',
        'tokendevice',
        'agent',
        'ip'
    ];

    public function user() {
        return $this->morphTo();
    }

    public function app() {
        return $this->belongsTo(App::class, 'app_id', 'id');
    }


}
