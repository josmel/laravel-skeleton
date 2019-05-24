<?php namespace App\Models;

use  Illuminate\Database\Eloquent\Model;
class AdminPasswordReset extends Model {

    /**
     * Generated
     */
    
    protected $table = 'admin_password_resets';
    public $timestamps = true;
    protected $guarded = [];
    protected $fillable = [
        'email',
        'token'
    ];



}
