<?php namespace App\Models;

use  Illuminate\Database\Eloquent\Model;
class Address extends Model {

    /**
     * Generated
     */

    const TABLE='address';
    protected $table = self::TABLE;
    public $timestamps = true;
    protected $guarded = [];
    protected $fillable = [
        'client_id','address'
    ];
    protected $hidden =['created_at','updated_at','deleted_at'];

   

}
