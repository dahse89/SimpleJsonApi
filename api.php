<?php
require_once 'JsonApi/Enums/ServerStatus.php';
require_once 'JsonApi/Enums/StatusMessages.php';
require_once 'JsonApi/JsonApi.php';

use JsonApi\JsonApi;
$key = "test";

class MyJsonApi{
    public function test($x,$y,$z = 0){
        return $x*2;
    }

    private function test2($y){
        return $y + $y;
    }
}


$API = new JsonApi(new MyJsonApi());
$API->setAuth($key);
$API->receivePOST();