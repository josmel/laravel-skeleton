<?php namespace App\Models;

use  Illuminate\Database\Eloquent\Model;
class ProviderQuotation extends Model {

    /**
     * Generated
     */
    
    protected $table = 'provider_quotation';
    public $timestamps = true;
    protected $guarded = [];
    protected $fillable = [
        'id',
        'provider_id',
        'quotation_id',
        'price'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at',
    ];


    public function items()
    {
        return $this->belongsToMany(Item::class,
        'details','provider_quotation_id','item_id');
    }
}
