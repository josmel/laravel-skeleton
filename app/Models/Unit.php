<?php namespace App\Models;

use  Illuminate\Database\Eloquent\Model;
class Unit extends Model {

    /**
     * Generated
     */

    const TABLE='units';
    protected $table = self::TABLE;
    public $timestamps = true;
    protected $guarded = [];
    protected $fillable = [
        'name'
    ];
    protected $hidden =['created_at','updated_at','deleted_at'];

    static function getAll(){

        {
        return Self::all();
        }

    }

}
