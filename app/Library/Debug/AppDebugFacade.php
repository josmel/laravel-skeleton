<?php

namespace App\Library\Debug;

use Illuminate\Support\Facades\Facade;

class AppDebugFacade extends Facade {

    protected static function getFacadeAccessor()
    {
        return AppDebug::class;
    }

}