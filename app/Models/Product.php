<?php namespace App\Models;

use  Illuminate\Database\Eloquent\Model;
class Product extends Model {

    /**
     * Generated
     */
    
    protected $table = 'products';
    public $timestamps = true;
    protected $guarded = [];
    protected $fillable = [
        'id',
        'name',
        'description'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at',
    ];



    public static function getProduct($product){

        return self::where('name', 'like', '%'.$product.'%');
    }
}
