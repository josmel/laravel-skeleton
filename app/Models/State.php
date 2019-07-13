<?php namespace App\Models;

use  Illuminate\Database\Eloquent\Model;
class state extends Model {

    /**
     * Generated
     */

    const TABLE='state';
    protected $table = self::TABLE;
    public $timestamps = true;
    protected $guarded = [];
    protected $fillable = [
        'name'
    ];
    protected $hidden =['created_at','updated_at','deleted_at'];

   
    public function quotations()
    {
        return $this->hasMany(Quotation::class, 'state_id', 'id');
    }


    public static function getAllQuotations($user_id)
    {
       return  self::with(['quotations'=> function ($query) use ($user_id){
            $query->where('quotations.client_id', $user_id)
              ->with(['items','providers']);
        }])
        ->withCount(['quotations'=> function ($query) use ($user_id) {
            $query->where('quotations.client_id',
            $user_id);
        }])
        ->get();
    }
}
