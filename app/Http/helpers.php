<?php

use Illuminate\Contracts\View\Factory as ViewFactory;

if (! function_exists('viewc')) {
    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string  $view
     * @param  array   $data
     * @param  array   $mergeData
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    function viewc($view = null, $data = [], $mergeData = [])
    {
        if(isset($data['menu']))
            throw new \Exception("Menú es una palabra reservada");

        $router = Route::getCurrentRoute()->getActionName();

        $parseRouter= explode('\\',$router);
        $countParseRouter= count($parseRouter)-1;
        $dataControllerAction= explode('@',$parseRouter[$countParseRouter]);
        $countParseRouter--;

        $mod= (isset($parseRouter[$countParseRouter]))? strtolower($parseRouter[$countParseRouter]) : 'index';
        $controller= strtolower(str_replace('Controller', '',$dataControllerAction[0]));
        $action= strtolower($dataControllerAction[1]);

        $route = ['module' => $mod,
            'controller' => $controller,
            'action' => $action,
        ];
        $data = array_merge($data, $route);

        if (Auth::check()) {
            $dataRoles = Auth::user()->cachedRoles();

            if (count($dataRoles) == 0)
                throw new \Exception("Usuario inválido");

            $currentRole = $dataRoles[0];
            $menuService = new \App\Services\MenuService();
            $data['menu'] = $menuService->getDataMenuByRole($currentRole->name, $route);
        }

        $factory = app(ViewFactory::class);

        if (func_num_args() === 0) {
            return $factory;
        }

        return $factory->make($view, $data, $mergeData);
    }
}