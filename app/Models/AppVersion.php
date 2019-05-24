<?php namespace App\Models;

use  Illuminate\Database\Eloquent\Model;
class AppVersion extends Model {

    /**
     * Generated
     */
    
    protected $table = 'app_versions';
    public $timestamps = true;
    protected $guarded = [];
    protected $fillable = [
        'id',
        'app_id',
        'name',
        'description'
    ];
    const TABLE_NAME= 'app_versions';

    public function app() {
        return $this->belongsTo(\App\Models\App::class, 'app_id', 'id');
    }


}
