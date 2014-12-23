<?php
namespace JsonApi;

use JsonApi\Enums\ServerStatus;
use JsonApi\Enums\StatusMessages;

class JsonApi
{
    private $Auth;
    private $request;
    private $api;

    public function __construct($api){
        $this->api = $api;
    }

    public function setAuth($secret){
        $this->Auth = $secret;
    }

    public function  receivePOST(){
        $this->request = file_get_contents('php://input');

        $header = getallheaders();
        $request = json_decode($this->request,true);
        $this->validateHeader($header);
        $request = $this->validateRequest($request);
        $res = call_user_func_array(array($this->api,$request['method']),$request['params']);
        $this->endOk($res);
    }

    private function checkIfReqMethod($request){
        $rfc = new \ReflectionClass($this->api);

        if(!$rfc->hasMethod($request['method'])){
            $this->endError("Method {$request['method']}() not exist",ServerStatus::NOT_FOUND);
        }
        $method = $rfc->getMethod($request['method']);
        if(!$method->isPublic()){
            $this->endError("Method {$request['method']}() not exist",ServerStatus::NOT_FOUND);
        }

        $np = count($request['params']);
        $nrp = $method->getNumberOfRequiredParameters();
        $nmp = $method->getNumberOfParameters();
        if($np < $nrp || $np > $nmp){
            if($nrp !== $nmp){
                $error = "requires min. $nrp and accept max. $nmp parameter(s), given: $np";
            }else{
                $error = "accept max. $nmp parameter(s), given: $np";
            }
            $this->endError("Method {$request['method']}() $error",ServerStatus::BAD_REQUEST);
        }
    }

    private function validateRequest($request){
        if(!$this->request){
            $this->endError("Empty request",ServerStatus::BAD_REQUEST);
        }
        if(!array_key_exists('method',$request)){
            $this->endError("Missing 'method' field in request",ServerStatus::BAD_REQUEST);
        }

        if(!is_array(@$request['params'])){
            $request['params'] = isset($request['params']) ? array($request['params']) : array();
        }
        $this->checkIfReqMethod($request);
        return $request;
    }

    private function validateHeader($header){
        if(!is_array($header)){
            $this->endError("invalid JSON in request",ServerStatus::BAD_REQUEST);
        }
        if(@$header['Authorization'] !== $this->Auth){
            $this->endError("Missing header authorization",ServerStatus::UNAUTHORIZED);
        }
    }

    public function endError($msg,$status){
        header('Content-Type: application/json');
        header(StatusMessages::headerConform((int)$status));

        $this->end(array(
            'error' => array(
                'status' => $status,
                'description' => StatusMessages::fromServerStatus($status),
                'message' => $msg,
                'request' => $this->request,
                'at' => date("Y-m-d H:i:s")
            )
        ));
    }

    public function endOk($data){
        $status = 200;
        header('Content-Type: application/json');
        header(StatusMessages::headerConform((int)$status));

        $this->end(array(
            'success' => array(
                'status' => $status,
                'description' => StatusMessages::fromServerStatus($status),
                'data' => $data,
                'request' => $this->request,
                'at' => date("Y-m-d H:i:s")
            )
        ));
    }

    public function end($arrMsg){

        die(json_encode($arrMsg,JSON_PRETTY_PRINT));
    }
}