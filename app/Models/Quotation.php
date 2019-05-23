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
             'client_id','category_id','price',
             'description','address','type','date'
    ];
    protected $hidden =['updated_at','deleted_at'];
    public function products()
    {
        return $this->belongsToMany(
            Category::class,
            'category_quotation',
            'quotation_id',
            'category_id')
            ->withPivot('quantity','description');
    }

}
