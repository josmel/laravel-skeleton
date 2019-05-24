<?php
/**
 * Created by PhpStorm.
 * User: josmel
 * Date: 27/02/17
 * Time: 04:20 PM
 */

namespace App\Services;

use App\Http\Requests\RequestService;
use App\Models\Gasonet\Eess;
use App\Models\Setting;
use Illuminate\Http\Request;
class EessService
{

    public function getSeason($id=null,$find=false,$gasoline=false)
    {
        try {
            $modelEess = new Eess();
            $response = $modelEess->listSeason($id,$find,$gasoline);
        } catch (\Exception $ex) {
             throw  new  \Exception('Error en el servidor');
        }
        return $response;
    }


    /**
     * @param Request $request
     * @throws \Exception
     */
    public  function getCoordinates(Request $request){
        $radio = Setting::getSetting();
        $latitude=null;
        $longitude=null;
        if(!empty($request->coordinates) && !is_null($request->coordinates)){
            $result=explode(',',$request->coordinates);
            if(count($result)!=2) {
                $modeee = new RequestService();
                $d= $modeee->response([
                    "coordinates"=>"coordwwwwwwwwwwwwwwwinates"
                ]);
                dd($d);
                throw  new \Exception('entrada incorrecta');
            }
            $latitude=null;
        }
        dd($request->coordinates);

        dd($request->all());
    }


}