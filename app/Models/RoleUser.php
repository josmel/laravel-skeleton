<?php namespace App\Models;

use  Illuminate\Database\Eloquent\Model;
class RoleUser extends Model {

    /**
     * Generated
     */
    
    protected $table = 'role_user';
    public $timestamps = false;
    protected $guarded = [];
    protected $fillable = [
        'role_id',
        'user_id',
        'user_type'
    ];

    const TABLE_NAME='role_user';


    public function user() {
        return $this->morphTo();
    }

    public function role() {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }


}
