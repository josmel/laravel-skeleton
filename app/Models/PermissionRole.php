<?php namespace App\Models;

use  Illuminate\Database\Eloquent\Model;
class PermissionRole extends Model {

    /**
     * Generated
     */
    
    protected $table = 'permission_role';
    public $timestamps = false;
    protected $guarded = [];
    protected $fillable = [
        'permission_id',
        'role_id'
    ];


    public function permission() {
        return $this->belongsTo(\App\Models\Permission::class, 'permission_id', 'id');
    }

    public function role() {
        return $this->belongsTo(\App\Models\Role::class, 'role_id', 'id');
    }


}
