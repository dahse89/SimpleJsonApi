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
