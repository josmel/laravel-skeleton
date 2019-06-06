<?php namespace App\Models;

use  Illuminate\Database\Eloquent\Model;
class Item extends Model {

    /**
     * Generated
     */

    const TABLE='items';
    protected $table = self::TABLE;
    public $timestamps = true;
    protected $guarded = [];
    protected $fillable = [
        'brand_id','product_id','quotation_id','quantity','product','brand'
    ];
    protected $hidden =['created_at','updated_at','deleted_at'];


}
