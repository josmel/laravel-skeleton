<?php namespace App\Models;

use  Illuminate\Database\Eloquent\Model;
class ClientPasswordReset extends Model {

    /**
     * Generated
     */
    
    protected $table = 'client_password_resets';
    public $timestamps = true;
    protected $guarded = [];
    protected $fillable = [
        'email',
        'token'
    ];



}
