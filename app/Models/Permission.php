<?php namespace App\Models;

use Zizaco\Entrust\EntrustPermission;
class Permission extends EntrustPermission {

    /**
     * Generated
     */
    
    protected $table = 'permissions';
    public $timestamps = true;
    protected $guarded = [];
    protected $fillable = [
        'id',
        'name',
        'display_name',
        'description'
    ];

    const PREFIX_CACHE = 'permissions_';
    const CACHE_MINUTES = 60;
    protected $hidden = [
        'created_at','updated_at','deleted_at','pivot','controller'
    ];
    public function roles() {
        return $this->belongsToMany(Role::class, 'permission_role', 'permission_id', 'role_id');
    }

    public function permissionRoles() {
        return $this->hasMany(PermissionRole::class, 'permission_id', 'id');
    }


    static function getPermission($role){

        {
           // if (!\Cache::has(self::PREFIX_CACHE)) {
                $permissions=$role->permissions->groupBy('controller');
                \Cache::put(self::PREFIX_CACHE, $permissions, self::CACHE_MINUTES);
           // }

            return  \Cache::get(self::PREFIX_CACHE);
        }

    }

}
