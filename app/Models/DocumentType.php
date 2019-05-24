<?php namespace App\Models;

use  Illuminate\Database\Eloquent\Model;
class DocumentType extends Model {

    /**
     * Generated
     */
    
    protected $table = 'document_types';
    public $timestamps = true;
    protected $guarded = [];
    protected $fillable = [
        'id',
        'name',
        'display_name',
        'flagactive'
    ];

    protected $hidden = [
      'created_at','updated_at','deleted_at','flagactive'
    ];
    const CACHE_MINUTES = 60;
    const PREFIX_CACHE = 'document_types';

    static function getDocumentType(){

        {
            if (!\Cache::has(self::PREFIX_CACHE)) {
                $documents =self::whereFlagactive(BaseModel::STATE_FLAGACTIVE)
                    ->get();
                \Cache::put(self::PREFIX_CACHE, $documents, self::CACHE_MINUTES);
            }
            return  \Cache::get(self::PREFIX_CACHE);
        }

    }
   
}
