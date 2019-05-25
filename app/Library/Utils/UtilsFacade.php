<?php

namespace App\Library\Utils;

use Illuminate\Support\Facades\Facade;

class UtilsFacade extends Facade {

    protected static function getFacadeAccessor()
    {
        return Utils::class;
    }

}