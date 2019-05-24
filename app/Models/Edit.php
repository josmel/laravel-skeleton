<?php namespace App\Models;

use  Illuminate\Database\Eloquent\Model;
class Edit extends Model {

    /**
     * Generated
     */
    
    protected $table = 'edits';
    public $timestamps = true;
    protected $guarded = [];
    protected $fillable = [
        'id',
        'admin_id',
        'edit_id',
        'edit_type'
    ];


    public function admin() {
        return $this->belongsTo(\App\Models\Admin::class, 'admin_id', 'id');
    }


}
