<?php namespace App\Models;

use Zizaco\Entrust\EntrustRole;
use Cache;
class Role extends EntrustRole {

    /**
     * Generated
     */
    
    protected $table = 'roles';
    public $timestamps = true;
    protected $guarded = [];
    protected $fillable = [
        'id',
        'name',
        'display_name',
        'description'
    ];
    const PREFIX_CACHE = 'roles_';
    const CACHE_MINUTES = 1440;
    const USER_APP_CLIENT = 'user_client';
    const USER_APP_PROVIDER = 'user_provider';
    
    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];


    public function routeTypes() {
        return $this->belongsToMany(\App\Models\RouteType::class, 'menus', 'role_id', 'route_type_id');
    }

    public function permissions() {
        return $this->belongsToMany(\App\Models\Permission::class, 'permission_role', 'role_id', 'permission_id');
    }

    public function menus() {
        return $this->hasMany(\App\Models\Menu::class, 'role_id', 'id');
    }

    public function permissionRoles() {
        return $this->hasMany(\App\Models\PermissionRole::class, 'role_id', 'id');
    }

    public function roleUsers() {
        return $this->hasMany(\App\Models\RoleUser::class, 'role_id', 'id');
    }

    static function getRoleByName($name) {
        $state = Cache::remember(self::PREFIX_CACHE . $name, self::CACHE_MINUTES, function() use ($name) {
            return self::where('name', $name)->first();
        });

        if (!$state)
            throw new \Exception("Nombre de rol inválido");

        return $state;
    }

    static function getRole($user){

        {
//            if (!\Cache::has(self::PREFIX_CACHE)) {
                return $user->roles()
                    ->first()->role;
               // \Cache::put(self::PREFIX_CACHE, $role, self::CACHE_MINUTES);
//            }

           // return  \Cache::get(self::PREFIX_CACHE);
        }

    }


}
