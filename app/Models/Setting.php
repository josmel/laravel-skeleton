<?php namespace App\Models;

use  Illuminate\Database\Eloquent\Model;
class Setting extends Model {

    /**
     * Generated
     */
    
    protected $table = 'settings';
    public $timestamps = true;
    protected $guarded = [];
    protected $fillable = [
        'id',
        'radio',
        'radio_map',
        'phone_gasonet'
    ];
    const CACHE_MINUTES = 60;
    const PREFIX_CACHE = 'settings';
    protected $hidden = [
        'created_at','updated_at','flagactive'
    ];

        static function getSetting(){

            {
//                if (!\Cache::has(self::PREFIX_CACHE)) {
                    $result=self::first();
                    $settings =(is_null($result))?null:$result;
                    \Cache::put(self::PREFIX_CACHE, $settings, self::CACHE_MINUTES);
  //              }

                return  \Cache::get(self::PREFIX_CACHE);
            }

        }

}
