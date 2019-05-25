<?php

namespace App\Library\Utils;

use Illuminate\Http\Request;
use ReflectionClass;
use App;

class Utils
{

    public static function getDocumentMethodh($class)
    {
        $method = ['edit', 'store', 'update', 'destroy', 'show', '__construct'];
        $route_class = 'App\Http\Controllers\\' . $class;
        $reflector = new ReflectionClass($route_class);
        $result = [];
        foreach ($reflector->getMethods(256) as $value) {
            if ($value->class == $route_class && !(in_array($value->getName(), $method, true))) {
                $comments = $value->getDocComment();
                $result[] = ['name' => $value->getName(),
                    'description' => isset(explode("\n", $comments)[1]) ?
                        explode("\n", $comments)[1] : $value->getName()];
            }
        }
        return $result;
    }


    public static function getUserAgent()
    {
        return (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : '';
    }

    public static function getUserIp()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
            return $_SERVER['HTTP_CLIENT_IP'];

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            return $_SERVER['HTTP_X_FORWARDED_FOR'];

        return (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : '';
    }

    public static function compareVersion($version, $compareversion, $operator = '==')
    {
        return version_compare($version, $compareversion, $operator);
    }

    public function getPermissionByRequest(Request $request)
    {
        $actionName = $request->route()->getActionName();
        $arrRoute = explode("\\", $actionName);
        $count = count($arrRoute) - 1;
        $arrAction = explode("@", $arrRoute[$count]);

        return $arrRoute[$count - 1] . "\\" . $arrAction[0];
    }

    public function createImageToString($strImage, $folder, $resize = false)
    {
        $strImage = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $strImage));
        $image = imagecreatefromstring($strImage);
        $dataImage = getimagesizefromstring($strImage);
        $widthImage = $dataImage[0];
        $heightImage = $dataImage[1];
        list(, $extension) = explode('/', $dataImage["mime"]);
        $extension = $this->getPrettyExtension($extension);

        $name=date('YmdHis') . rand(1, 1000) . "." . $extension;
        $pathrelative = $folder . "/" . $name;
        $path = storage_path('app/public') . '/' . $pathrelative;

        if ($resize === false) {
            $this->createImage($image, $path, $extension);
        } else {
            if (count($resize) != 2)
                throw new \Exception("Parámetros de redimencionamiento inválidos");

            list($newWith, $newHeight) = $resize;
            list($withFinal, $heightFinal) = $this->getFinalSizeResize($widthImage, $heightImage, $newWith, $newHeight);

            $canvas = imagecreatetruecolor($withFinal, $heightFinal);
            imagecopyresampled($canvas, $image, 0, 0, 0, 0, $withFinal, $heightFinal, $widthImage, $heightImage);
            imagedestroy($image);
            $this->createImage($canvas, $path, $extension);
        }

        return $name;
    }

    public function getPrettyExtension($extension)
    {
        $extension = strtolower($extension);
        $dataExtensions = ['jpg', 'jpeg', 'png'];

        if (!in_array($extension, $dataExtensions))
            throw new \Exception("Imagen con extensión inválida");

        if ($extension == 'jpeg')
            return 'jpg';

        return $extension;
    }

    public function getUrlDinamic($url)
    {
        return asset('/dinamic/' . $url);
    }

    private function getFinalSizeResize($width, $height, $newWidth, $newHeight)
    {
        $x_ratio = $newWidth / $width;
        $y_ratio = $newHeight / $height;
        if (($width <= $newWidth) && ($height <= $newHeight)) {
            $lastWidth = $width;
            $lastHeight = $height;
        } elseif (($x_ratio * $height) < $newHeight) {
            $lastHeight = ceil($x_ratio * $height);
            $lastWidth = $newWidth;
        } else {
            $lastWidth = ceil($y_ratio * $width);
            $lastHeight = $newHeight;
        }

        return [$lastWidth, $lastHeight];
    }

    private function createImage($image, $path, $extension = 'jpg', $quality = 90)
    {
        $dataExtensions = ['jpg', 'jpeg', 'png'];

        if (!in_array($extension, $dataExtensions))
            throw new \Exception("Imagen con extensión inválida");

        $processImage = [
            'jpg' => function ($image, $path, $quality) {
                imagejpeg($image, $path, $quality);
            },
            'jpeg' => function ($image, $path, $quality) {
                imagejpeg($image, $path, $quality);
            },
            'png' => function ($image, $path, $quality) {
                imagepng($image, $path, $quality);
            }
        ];

        if ($extension == 'png') {
            $q = 9 / 100;
            $quality *= $q;
        }

        $processImage[$extension]($image, $path, $quality);
    }

    static function getBoundaries($lat = null, $lng = null, $distance = null, $earthRadius = null)
    {
        $lat = (is_null($lat)) ? env('LATITUDE') : $lat;
        $lng = (is_null($lng)) ? env('LONGITUDE') : $lng;
        $distance = (is_null($distance)) ? env('RADIO') : $distance;
        $earthRadius = (is_null($earthRadius)) ? env('EARTHRADIUS') : $earthRadius;

        $return = [];
        // Los angulos para cada dirección
        $cardinalCoords = array('north' => '0',
            'south' => '180',
            'east' => '90',
            'west' => '270');
        $rLat = deg2rad($lat);
        $rLng = deg2rad($lng);
        $rAngDist = $distance / $earthRadius;
        foreach ($cardinalCoords as $name => $angle) {
            $rAngle = deg2rad($angle);
            $rLatB = asin(sin($rLat) * cos($rAngDist) + cos($rLat) * sin($rAngDist) * cos($rAngle));
            $rLonB = $rLng + atan2(sin($rAngle) * sin($rAngDist) * cos($rLat), cos($rAngDist) - sin($rLat) * sin($rLatB));
            $return[$name] = array('lat' => (float)rad2deg($rLatB),
                'lng' => (float)rad2deg($rLonB));
        }
        return array('min_lat' => $return['south']['lat'],
            'max_lat' => $return['north']['lat'],
            'min_lng' => $return['west']['lng'],
            'max_lng' => $return['east']['lng']);
    }

    static function distancia($point1, $point2)
    {
        return floor(acos(sin((double)$point1['latitude']) * sin((double)$point2['latitude']) +
                (cos((double)$point1['latitude']) * cos((double)$point2['latitude']) *
                    cos((double)$point1['longitude'] - (double)$point2['longitude']))) * (double)env('RADIOKM'));

    }

    static function getMiddlePoint($point1, $point2)
    {

        return [
            ($point1['latitude'] + $point2['latitude']) / 2,
            ($point1['longitude'] + $point2['longitude']) / 2
        ];

    }

    static function allCoordinates($point1, $point2, $distancemap = null)
    {
        $middle = self::getMiddlePoint($point1, $point2);
        $dis = self::distancia($point1, $point2);
        $value = round((($dis / 2) + ((($dis / 2)) / 3)), 2);
        $distance = is_nan($value) ? $distancemap : (($distancemap > $value) ? $distancemap : $value);
        return [
            'distance' => $distance,
            'middle' => $middle,
            'box' => self::getBoundaries($middle[0], $middle[1], $distance)
        ];
    }


    static function clearInputs($request)
    {
        $response = [];
        foreach ($request as $v => $k) {
            if (is_null($k)) {
                unset($request[$v]);
            } else {
                $response[$v] = $request[$v];
            }
        }
        return $response;
    }
}
