<?php

header("Access-Control-Allow-Origin:*");
header("Content-Type: application/json;");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Request-With");

include 'function.php';

$requestMethod = $_SERVER["REQUEST_METHOD"];

if($requestMethod == "GET"){
    if(isset($_GET['nid'])){
        // echo $_GET['nid'];
        $build_token = buildToken($_GET);
         echo $build_token;
    }
    else{
        $data = [
            'status' => 401,
            'message' => " You Must Pass Your NID to get record",
        ];
        header("HTTP/1.0 401 MeThod Not Allowed");
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