<?php

header("Access-Control-Allow-Origin:*");
header("Content-Type: application/json;");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Request-With");

include 'function.php';

$requestMethod = $_SERVER["REQUEST_METHOD"];

// $inputRecord = json_decode(file_get_contents("php://input"), true);

if($requestMethod == "POST"){
    if(isset($_GET['key'], $_GET['key_id'])){
        if(isset($_GET['nid'])){
            if(isset($_GET['name'], $_GET['email'], $_GET['phone'])){
                if(empty($inputRecord)){
                    $storeRecord = storeRecordFinal($_GET);
                }
                else{
                    $storeRecord = storeRecordFinal($inputRecord);
                }
                echo $storeRecord;
            }
            else{
                $data = [
                    'status' => 401,
                    'message' => " You Must Pass Your Name, Email, Password to store record",
                ];
                header("HTTP/1.0 401 NID is not found");
                echo json_encode($data);
            }
           
        }
        else{
            $data = [
                'status' => 401,
                'message' => " You Must Pass Your NID to store record",
            ];
            header("HTTP/1.0 401 NID is not found");
            echo json_encode($data);
        }
    }
    else{
       $data = [
            'status' => 401,
            'message' => "Pass Your API Token and TokenID to store record",
        ];
        header("HTTP/1.0 401 Invalid Token or TokenID");
        echo json_encode($data); 
    }
    // $inputRecord = json_decode(file_get_contents("php://input"), true);
}
else{
    $data = [
        'status' => 405,
        'message' => $requestMethod. " Method Not Allow",
        ];
        header("HTTP/1.0 405 MeThod Not Allowed");
        echo json_encode($data);
}




?>