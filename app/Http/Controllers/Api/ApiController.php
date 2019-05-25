<?php
/**
 * Created by PhpStorm.
 * User: josmel
 * Date: 24/02/17
 * Time: 01:23 PM
 */

namespace App\Http\Controllers\Api;

use App\Services\ResponseApiService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

abstract class ApiController extends Controller
{

    protected $_response;
    protected $_identity = null;
    protected $_guard = null;

    public function __construct()
    {
        $new=Route::getCurrentRoute()->getAction()['middleware'];
        $guard = is_array($new)?$new[0]:$new;
        $this->_response = new ResponseApiService();
        if (is_null($this->_identity)) {
            $this->_identity = auth()->guard($guard)->user();
        }
        $this->_guard = $guard;
    }

}