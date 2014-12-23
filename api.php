<?php
/* include files */
require_once 'JsonApi/Enums/ServerStatus.php';
require_once 'JsonApi/Enums/StatusMessages.php';
require_once 'JsonApi/JsonApi.php';
/* import namespace */
use JsonApi\JsonApi;

/* define some authorization key */
$key = "test";

/* - define API Class with API method
   - should be in a differnet file */
class MyJsonApi{
    /**
     * Public method are available
     * simply return  the response
     * @param $x
     * @param $y
     * @param int $z
     * @return mixed
     */
    public function test($x,$y,$z = 0){
        return $x*2;
    }

    /**
     * The api will respond the array as JSON
     * @return array
     */
    public function returnStructure(){
        return array('x' => 1,'y'=>array('z' => 2));
    }

    /**
     * All non public methods are not available by API
     * @param $y
     * @return mixed
     */
    private function test2($y){
        return $y + $y;
    }
}
/* create JSON API out of your class */
$API = new JsonApi(new MyJsonApi());
/* set the authorization key, will be used in header authorization */
$API->setAuth($key);
/* arm the API */
$API->receivePOST();