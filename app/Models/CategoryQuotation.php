<?php namespace App\Models;

use  Illuminate\Database\Eloquent\Model;
class CategoryQuotation extends Model {

    /**
     * Generated
     */

    const TABLE='category_quotation';
    protected $table = self::TABLE;
    public $timestamps = true;
    protected $guarded = [];
    protected $fillable = [
        'category_id','quantity','description','quotation'
    ];
    protected $hidden =['created_at','updated_at','deleted_at'];

    static function getAll(){

        {
        return Self::all();
        }

    }

}
