SimpleJsonApi
=============

A simple Json API. Methods can be defined in a class and will be available by HTTP POST. This API has full JSON support, uses header authorization and deal with corret server status codes.

**Create an API** simply from a class:
```php
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
```
A general way to call the API is using CURL:
for example:
```bash
 curl -XPOST -H 'Authorization: test' localhost/JsonApi/api.php -d '{"method":"test","params":[1,2,3]}'
```
A reponse could be:
```json
{
    "success": {
        "status": 200,
        "description": "Standard response for successful HTTP requests",
        "data": 2,
        "request": "{\"method\":\"test\",\"params\":[1,2,3]}",
        "at": "2014-12-23 10:07:02"
    }
}
```
Otherwise there are also detailed error messages:
```json
{
    "error": {
        "status": 404,
        "description": "Not Found: The requested resource could not be found.",
        "message": "Method test2() not exist",
        "request": "{\"method\":\"test2\",\"params\":2}",
        "at": "2014-12-23 09:56:54"
    }
}
```
```json
{
    "error": {
        "status": 401,
        "description": "Unauthorized: Authorization missing or invalid.",
        "message": "Missing header authorization",
        "request": "",
        "at": "2014-12-23 11:06:59"
    }
}

{
    "error": {
        "status": 400,
        "description": "Bad Request: The request wasn't valid.",
        "message": "Empty request",
        "request": "",
        "at": "2014-12-23 11:07:18"
    }
}

{
    "error": {
        "status": 400,
        "description": "Bad Request: The request wasn't valid.",
        "message": "Missing 'method' field in request",
        "request": "alskl",
        "at": "2014-12-23 11:09:14"
    }
}

{
    "error": {
        "status": 400,
        "description": "Bad Request: The request wasn't valid.",
        "message": "Method test() requires min. 2 and accept max. 3 parameter(s), given: 1",
        "request": "{\"method\":\"test\",\"params\":[1]}",
        "at": "2014-12-23 10:12:32"
    }
}

{
    "error": {
        "status": 400,
        "description": "Bad Request: The request wasn't valid.",
        "message": "Method test() accept max. 1 parameters, given: 3",
        "request": "{\"method\":\"test\",\"params\":[1,2,3]}",
        "at": "2014-12-23 10:06:22"
    }
}
```

That makes it possible to call the API from any programming language, here one example using PHP:
```php
<?php
/* define the call*/
$request = array(
    'method' => 'test',
    'params' => [1,3]
);
/* create request json string */
$json = json_encode($request);
/* define api url */
$ch = curl_init("localhost/SimpleJsonApi/api.php");
/* set request type to POST */
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
/* append JOSN request */
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
/* enable receiving response */
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
/* define some header informations */
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        /* Content-Type have to be json */
        'Content-Type: application/json',
        /* length can be set using string length*/
        'Content-Length: ' . strlen($json),
        /* key have to set as 'Authorization'*/
        'Authorization: test'
    )
);
/* do the call */
die(curl_exec($ch));
``` 
