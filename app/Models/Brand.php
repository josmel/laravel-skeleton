<?php namespace App\Models;

use  Illuminate\Database\Eloquent\Model;
class Brand extends Model {

    /**
     * Generated
     */
    
    protected $table = 'brands';
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


    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'brand_product',
            'brand_id',
            'product_id');
    }


    public static function getBrand($brand,$product){

        return self::where('name', 'like', '%'.$brand.'%')
                 ->whereHas('products', function ($query) use ($product){
                 $query->where('products.id',$product);
                });
    }

}