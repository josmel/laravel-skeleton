<?php namespace App\Models;

use  Illuminate\Database\Eloquent\Model;
class state extends Model {

    /**
     * Generated
     */

    const TABLE='states';
    protected $table = self::TABLE;
    public $timestamps = true;
    protected $guarded = [];
    protected $fillable = [
        'name'
    ];
    protected $hidden =['created_at','updated_at','deleted_at'];

   

}
