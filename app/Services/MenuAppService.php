<?php

namespace App\Services;

use App\Models\Menu;
use DB;
use App\Library\Utils\Utils;
use App\Models\RouteType;
use Illuminate\Http\Request;
use App\Models\Role;

class MenuAppService {





    public function  getMenu($role){
      return Menu::getMenuForRole($role);

    }

    public function  getStructure($role){


        return Menu::getMenuForRole($role);

    }
    public function getDataMenuByRole($role) {
        $modelRole = new Menu();
        $dataMenu = $modelRole->getMenuByRole($role);
        $menuSchema = [];
        $tmpChilds = [];

        foreach ($dataMenu as $menu) {

            $route = $this->getUrlMenuToRoute($menu->route, $menu->route_type_id);

            if ($menu->flagparent == 0) {
                if (isset($tmpChilds[$menu->id])) {
                    $menuSchema[$menu->id] = $tmpChilds[$menu->id];

                    $menuSchema[$menu->id]['name'] = $menu->name;
                    $menuSchema[$menu->id]['route'] = $route;
                } else {
                    $menuSchema[$menu->id] = [
                        'name' => $menu->name,
                        'route' => $route,
                        'hasChild' => 0,
                        'child' => [],
                    ];
                }
            } else {
                $dataChild = [
                    'name' => $menu->name,
                    'route' => $route,
                ];
                if (isset($menuSchema[$menu->menu_id])) {

                    $menuSchema[$menu->menu_id]['hasChild'] = 1;
                    $menuSchema[$menu->menu_id]['child'][] = $dataChild;
                }else {
                    if (isset($tmpChilds[$menu->menu_id])) {

                        $tmpChilds[$menu->menu_id]['child'][] = $dataChild;
                    }else {
                        $tmpChilds[$menu->menu_id] = [
                            'name' => '',
                            'route' => '',
                            'hasChild' => 1,
                            'child' => [$dataChild],
                        ];
                    }
                }
            }
        }

        return $menuSchema;
    }

    private function getUrlMenuToRoute($route, $type) {
        if (empty($route))
            return '';

        $actions = [
            Menu::TYPE_CONTROLLER => function($route) {
                return action($route . '@index');
            },
            Menu::TYPE_ACTION => function($route) {
                return action($route);
            },
            Menu::TYPE_CUSTOM => function($route) {
                return $route;
            }
        ];

        if (!isset($actions[$type]))
            throw new \Exception("Tipo de menú inválido");

        return $actions[$type]($route);
    }

    private function getActiveMenuToRoute($route, $type, $currentRoute) {
        $actions = [
            Menu::TYPE_CONTROLLER => function($route, $currentRoute) {
                $strCurrentRoute = $currentRoute['module'] . "\\" . $currentRoute['controller'] . "controller";
                return (strtolower($route) == $strCurrentRoute) ? 1 : 0;
            },
            Menu::TYPE_ACTION => function($route, $currentRoute) {
                $strCurrentRoute = $currentRoute['module'] . "\\" . $currentRoute['controller'] . "controller@" . $currentRoute['action'];
                return (strtolower($route) == $strCurrentRoute) ? 1 : 0;
            },
            Menu::TYPE_CUSTOM => function($route, $currentRoute) {
                return 0;
            }
        ];

        if (!isset($actions[$type]))
            throw new \Exception("Tipo de menú inválido");

        return $actions[$type]($route, $currentRoute);
    }

    public static function store(Request $request) {
        DB::beginTransaction();
        try {
            $dataRequest = $request->all();
            $role = Role::find($dataRequest ['role_id']);
            $role->menus()->forceDelete();
            foreach ($dataRequest['menus'] as $father) {
                $father['flagparent'] = 0;
                $father['flagremove'] = 0;
                $menu = $role->menus()->create($father);
                if ($father['children']) {
                    foreach ($father['children'] as $children) {
                        $children['flagparent'] = 1;
                        $children['flagremove'] = 0;
                        $children['menu_id'] = $menu->id;
                        $role->menus()->create($children);
                    }
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
        DB::commit();
        return;
    }

    public static function getRouteForRoleType($menu, $route_type) {
        try {
            $nameRouteType = $route_type->name;
            $controllers = [];
            switch ($nameRouteType) {
                case RouteType::type_controller:
                    $controllers = $menu->permissions;
                    break;
                case RouteType::type_action:
                    $controllers = $menu->permissions;
                    foreach ($controllers as $key => $controller) {
                        $controllers[$key]['actions'] = Utils::getDocumentMethodh($controller->name);
                    }
                    break;
                case RouteType::type_custom:
                    $controllers = [];
                    break;
                default:
                    break;
            }
            return $controllers;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

}
