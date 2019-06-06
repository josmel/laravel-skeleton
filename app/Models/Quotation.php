<?php namespace App\Models;

use  Illuminate\Database\Eloquent\Model;
class Quotation extends Model {

    /**
     * Generated
     */

    const TABLE='quotations';
    protected $table = self::TABLE;
    public $timestamps = true;
    protected $guarded = [];
    protected $fillable = [
             'client_id','type_payment',
             'description','address','type_address','date', 'title','specification'
    ];
    protected $hidden =['updated_at','deleted_at'];


    public function items()
    {
        return $this->belongsToMany(
            Product::class,
            'items',
            'quotation_id',
            'product_id')
            ->withPivot('quantity','brand','product');
    }

}
