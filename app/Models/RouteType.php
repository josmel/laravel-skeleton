<?php namespace App\Models;

class RouteType extends BaseModel  {

    /**
     * Generated
     */
    
    protected $table = 'route_type';
    public $timestamps = true;
    protected $guarded = [];
    protected $fillable = [
        'id',
        'name'
    ];


    public function roles() {
        return $this->belongsToMany(Role::class, 'menus', 'route_type_id', 'role_id');
    }

    public function menus() {
        return $this->hasMany(Menu::class, 'route_type_id', 'id');
    }


}
