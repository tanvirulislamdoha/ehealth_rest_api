<?php

header("Access-Control-Allow-Origin:*");
header("Content-Type: application/json;");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Request-With");

include 'function.php';

$requestMethod = $_SERVER["REQUEST_METHOD"];

if($requestMethod == "GET"){
    if(isset($_GET['key'], $_GET['key_id'])){
       
        if(isset($_GET['nid'])){
            // echo $_GET['nid'];
            $record = getRecord($_GET);
            echo $record;
        }
        else{
            $data = [
                'status' => 401,
                'message' => " You Must Pass Your NID to get record",
            ];
            header("HTTP/1.0 401 NID is not found");
            echo json_encode($data);

        }
    }
    else{
        $data = [
            'status' => 401,
            'message' => "Pass Your API key and KeyID to get record",
        ];
        header("HTTP/1.0 401 Invalid Key");
        echo json_encode($data);
    }
}
else{
    $data = [
        'status' => 405,
        'message' => $requestMethod. "Method Not Allow",
    ];
    header("HTTP/1.0 405 MeThod Not Allowed");
    echo json_encode($data);
}




?>