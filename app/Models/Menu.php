<?php namespace App\Models;

use DB;

class Menu extends BaseModel
{

    /**
     * Generated
     */

    protected $table = 'menus';
    public $timestamps = true;
    protected $guarded = [];
    protected $fillable = [
        'id',
        'roles',
        'route_type_id',
        'route',
        'name',
        'class_id',
        'order',
        'flagparent',
        'menu_id',
        'flagremove',
        'flagactive'
    ];
    const TYPE_CONTROLLER = 1;
    const TYPE_ACTION = 2;
    const TYPE_CUSTOM = 3;
    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at',
    ];

    const CACHE_MINUTES = 60;
    const PREFIX_CACHE = 'menus_';
    protected $casts = [
        'roles' => 'array',
    ];

    public function routeType()
    {
        return $this->belongsTo(RouteType::class, 'route_type_id', 'id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'menu_id', 'id');
//            ->select('id','name','route');
    }

    public function getMenuByRole($role)
    {
        $query = $this->where('role_id', $role->id)
            ->where('flagactive', self::STATE_FLAGACTIVE)
            ->orderBy('order', 'ASC')
            ->select($this->table . '.*');

        return $query->get();
    }


    public static function getMenuForRole($role_id)
    {

//       if (!\Cache::has(self::PREFIX_CACHE)) {
            $response = self::whereFlagparent(0)
                ->whereFlagactive(1)
                   ->whereRaw("roles->'$[*].id' like '%$role_id%'")
                ->with(['children' => function ($query) {
                    $query->whereFlagactive(1)
                        ->select('id','menu_id', 'name', 'route');
                }])
                ->select('id', 'name', 'route',
                  DB::raw("IF((select count(t.id) from menus as t where t.menu_id=menus.id)=0,0,1 ) AS hasChild "))
                ->get();
//          \Cache::put(self::PREFIX_CACHE, $response, self::CACHE_MINUTES);
//        }
        return $response;
//        return \Cache::get(self::PREFIX_CACHE);


    }

}
