<?php
/**
 * Created by PhpStorm.
 * User: josmel
 * Date: 24/02/17
 * Time: 01:05 PM
 */

namespace App\Services;

use App\Models\App,
    App\Library\Utils\Utils,
    Cache,
    Config;
use App\Models\AppVersion;


class AppService {

    public function verifyVersion($idApp, $version) {
        $modelApp = new App;
        $minutes = Config::get('cache.ttl');

        $objApp = Cache::remember(App::PREFIX_CACHE . $idApp, $minutes, function() use ($modelApp, $idApp) {
          
            $objApp = $modelApp->getAppById($idApp);
           
            if (!$objApp)
                throw new \Exception("Id App inválido");

            return $objApp;
        });

       
        if (!Utils::compareVersion($version, $objApp->appversion, $objApp->rule))
            throw new \Exception("Actualice su aplicación, la versión está obsoleta");

        return $objApp;
    }

    public function find($id = null) {
        if (!empty($id)) {
            $model = App::find($id);

            if (!$model)
                throw new \Exception("Id inválido");
        }else {
            $model = new App;
        }

        return $model;
    }

    public function create($data) {
        $dataApp = [
            "name" => $data['name'],
            "typedevice" => $data['typedevice'],
            "rule" => App::DEFAULT_OPERATOR,
        ];
        $modelApp = App::create($dataApp);

        $dataVersionApp = [
            "name" => $data['version'],
            "description" => $data['description'],
            "app_id" => $modelApp->id,
        ];
        $modelVersion = AppVersion::create($dataVersionApp);

        $modelApp->update(["app_version_id" => $modelVersion->id]);

        return $modelApp;
    }

    public function update(App $app, $data) {
        if (!empty($data['version_name'])) {
            $appversion = $app->versions()
                ->create(['name' => $data['version_name'],
                    'description' => $data['version_description']]);
            $app->update(["app_version_id" => $appversion->id]);
        }
        return $app->update($data);
    }

    public function delete($id) {
        $model = $this->find($id);
        $model->delete();
    }

}
