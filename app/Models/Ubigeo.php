<?php namespace App\Models;

use  Illuminate\Database\Eloquent\Model;
class Ubigeo extends Model {

    /**
     * Generated
     */
    
    protected $table = 'ubigeos';
    public $timestamps = true;
    protected $guarded = [];
    protected $fillable = [
        'id',
        'department',
        'province',
        'district',
        'ubigeo',
        'flagactive'
    ];



}
