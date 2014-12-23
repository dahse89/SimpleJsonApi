<?php
$request = array(
    'method' => 'test',
    'params' => [1,3]
);

$json = json_encode($request);
$ch = curl_init("localhost/JsonApi/api.php");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($json),
        'Authorization: test'
    )
);
die(curl_exec($ch));
