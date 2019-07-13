<?php namespace App\Models;

class Quotation extends BaseModel {

    /**
     * Generated
     */

    const TABLE='quotations';
    protected $table = self::TABLE;
    public $timestamps = true;
    protected $guarded = [];
    protected $fillable = [
             'client_id','type_payment','state_id',
             'description','address','type_address','date', 'title','specification'
    ];
    protected $hidden =['updated_at','deleted_at'];

    public function providers()
    {
        return $this->belongsToMany(Provider::class,'provider_quotation','quotation_id','provider_id')
        ->withPivot('price');
    }

    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'items',
            'quotation_id',
            'product_id')
            ->withPivot('quantity','brand','product');
    }


    public function items()
    {
        return $this->hasMany(Item::class, 'quotation_id', 'id');
    }


    public static function getQuotation($user_id,$id){

        return self::where('client_id',$user_id)
             ->where('id',$id)
            ->with('items')
            ->first();
    }

}
