<?php

require '../include/d_con.php';


function error422($message){
    $data = [
        'status' => 422,
        'message' => $message,
    ];
    header("HTTP/1.0 422 Unprocessable Inputs");
    echo json_encode($data);
    exit();
}




function storeRecordFinal($recordInput){
    global $conn;

    $nid = mysqli_real_escape_string($conn, $recordInput['nid']);
    $token = mysqli_real_escape_string($conn, $recordInput['key']);
    $tokenID = mysqli_real_escape_string($conn, $recordInput['key_id']);
    $name = mysqli_real_escape_string($conn, $recordInput['name']);
    $email = mysqli_real_escape_string($conn, $recordInput['email']);
    $phone = mysqli_real_escape_string($conn, $recordInput['phone']);
    
    // echo $nid;
    if(strlen($nid) != 10 && strlen($nid) != 13){
        return error422('Incorrect Health ID');
    }

    $nidCheck = "SELECT * FROM api_token WHERE nid = '$nid' AND id = '$tokenID'" ;
    $get_nidCheck = mysqli_query($conn, $nidCheck);

        if($get_nidCheck){
            if(mysqli_num_rows($get_nidCheck)>0){
                $tokenQuery = mysqli_query($conn, "SELECT *  FROM api_token WHERE nid = '$nid' AND token = '$token' And id='$tokenID'");
                
                if(mysqli_num_rows($tokenQuery)>0){
                    $tokenCheck = mysqli_fetch_assoc($tokenQuery);

                    if($tokenCheck['status'] == 1){
                        if($recordInput['name'] == null || $recordInput['email'] == null || $recordInput['phone'] == null){
                            return error422('You must have to add name, email, phone');
                        }
                        

                        if($tokenCheck['hit_limit'] <= $tokenCheck['hit_counter']){
                            $data = [
                                'find' => $tokenCheck['hit_limit'].$tokenCheck['hit_counter'],
                                'status' => 404,
                                'message' => "API Hit Limit Crossed",
                            ];
                            header("HTTP/1.0 404 API Hit Limit Crossed");
                            return json_encode($data);
                        }
                        else{
                            mysqli_query($conn, "UPDATE api_token SET hit_counter=hit_counter+1 WHERE token =$token AND id='$tokenID'");
                        }

                        

                        $query = "INSERT INTO medical_record (hospital_name,dr_name,nid) VALUES ('$name','$email','$phone')";
                        $get_query = mysqli_query($conn, $query);

                                if($get_query){

                                    mysqli_query($conn, "UPDATE api_token SET hit_counter=hit_limit-1 WHERE token =$token AND id = '$tokenID'");

                                    $res = $name.$email.$phone;
                                        // $response = mysqli_fetch_all($get_query, MYSQLI_ASSOC);
                            
                                        $data = [
                                            'status' => 200,
                                            'message' => "Record Showed Successfully",
                                            'record' => $res,
                                        ];
                                        header("HTTP/1.0 200 Success");
                                        return json_encode($data);
                            
                                        
                                    
                                }
                                else{
                                    $data = [
                                        'status' => 500,
                                        'message' => "Internal Server Error",
                                    ];
                                    header("HTTP/1.0 500 Internal Server Error");
                                    return json_encode($data);
                                }

                                }
                    else{
                        $data = [
                            'status' => 500,
                            'message' => "Invalid Status",
                        ];
                        header("HTTP/1.0 500 Invalid Status");
                        return json_encode($data);
                    }

                    

                }
                else{
                    $data = [
                        'status' => 404,
                        'message' => "Invalid API Token",
                    ];
                    header("HTTP/1.0 404 Invalid API Token");
                    return json_encode($data); 
                }
            }
            else{
                $data = [
                    'status' => 404,
                    'message' => "Invalid NID or TokenID",
                ];
                header("HTTP/1.0 404 Invalid NID or TokenID");
                return json_encode($data); 
            }
        }
        else{
            $data = [
                'status' => 500,
                'message' => "Internal Server Error",
            ];
            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);
        }
}



function getRecord($customer_p){
    global $conn;


    if($customer_p['nid'] == null){
        return error422('Enter  Your HealthID');
    }
    elseif(strlen($customer_p['nid']) != 10 && strlen($customer_p['nid']) != 13){
        return error422('Incorrect Health ID');
    }
    else{
        $token = mysqli_real_escape_string($conn, $customer_p['key']);
        $tokenID = mysqli_real_escape_string($conn, $customer_p['key_id']);
        $recordID = mysqli_real_escape_string($conn, $customer_p['nid']);

        $nidCheck = "SELECT status  FROM api_token WHERE nid = '$recordID' AND id ='$tokenID'" ;
        $get_nidCheck = mysqli_query($conn, $nidCheck);
 
       if($get_nidCheck){
            if(mysqli_num_rows($get_nidCheck)>0){
                $tokenQuery = mysqli_query($conn, "SELECT *  FROM api_token WHERE nid = '$recordID' AND token = '$token' AND id = $tokenID");
                
                if(mysqli_num_rows($tokenQuery)>0){
                    $tokenCheck = mysqli_fetch_assoc($tokenQuery);

                    if($tokenCheck['status'] == 1){
                        if($tokenCheck['hit_limit'] <= $tokenCheck['hit_counter']){
                            $data = [
                                'find' => $tokenCheck['hit_limit'].$tokenCheck['hit_counter'],
                                'status' => 404,
                                'message' => "API Hit Limit Crossed",
                            ];
                            header("HTTP/1.0 404 API Hit Limit Crossed");
                            return json_encode($data);
                        }
                        else{
                            mysqli_query($conn, "UPDATE api_token SET hit_counter=hit_counter+1 WHERE token =$token AND id = '$tokenID'");
                        }

                        $query = "SELECT *  FROM medical_record WHERE nid = '$recordID'";
                                $get_query = mysqli_query($conn, $query);

                                if($get_query){
                                    if(mysqli_num_rows($get_query)> 0){
                                        $response = mysqli_fetch_all($get_query, MYSQLI_ASSOC);
                            
                                        $data = [
                                            'status' => 200,
                                            'message' => "Record Showed Successfully",
                                            'record' => $response,
                                        ];
                                        header("HTTP/1.0 200 Success");
                                        return json_encode($data);
                            
                                        
                                    }
                                    else{
                                        $data = [
                                            'status' => 404,
                                            'message' => "No Record Found",
                                        ];
                                        header("HTTP/1.0 404 No Record Found");
                                        return json_encode($data);
                                    }
                                }
                                else{
                                    $data = [
                                        'status' => 500,
                                        'message' => "Internal Server Error",
                                    ];
                                    header("HTTP/1.0 500 Internal Server Error");
                                    return json_encode($data);
                                }

                                }
                    else{
                        $data = [
                            'status' => 500,
                            'message' => "Invalid Status",
                        ];
                        header("HTTP/1.0 500 Invalid Status");
                        return json_encode($data);
                    }

                    

                }
                else{
                    $data = [
                        'status' => 404,
                        'message' => "Invalid API Token",
                    ];
                    header("HTTP/1.0 404 Invalid API Token");
                    return json_encode($data); 
                }
            }
            else{
                $data = [
                    'status' => 404,
                    'message' => "Invalid NID or Token ID",
                ];
                header("HTTP/1.0 404 Invalid NID or Token ID");
                return json_encode($data); 
            }
        }
        else{
            $data = [
                'status' => 500,
                'message' => "Internal Server Error",
            ];
            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);
        }

       
    }
    

    // $recordID = mysqli_real_escape_string($conn, $customer_p['nid']);


    // if($recordID == null){
    //     return error422('Enter  Your HealthID');
    // }
    // elseif(strlen($recordID) != 10 && strlen($recordID) != 13){
    //     return error422('Incorrect Health ID');
    // }
    

    
    // $query = "SELECT *  FROM medical_record WHERE nid = '$recordID'";
    // $get_query = mysqli_query($conn, $query);

    

}


function buildToken($get_p){
    global $conn;


    if($get_p['nid'] == null){
        return error422('Enter  Your HealthID');
    }
    elseif(strlen($get_p['nid']) != 10 && strlen($get_p['nid']) != 13){
        return error422('Incorrect Health ID');
    }
    

    $recordID = mysqli_real_escape_string($conn, $get_p['nid']);


    // if($recordID == null){
    //     return error422('Enter  Your HealthID');
    // }
    // elseif(strlen($recordID) != 10 && strlen($recordID) != 13){
    //     return error422('Incorrect Health ID');
    // }
    

    $query = "SELECT *  FROM health_info WHERE nid = '$recordID'";
    $get_query = mysqli_query($conn, $query);
    

    if($get_query){
        if(mysqli_num_rows($get_query)> 0){

            $nid = $recordID;
            $token = rand(1234567890, 9000000000);
            $hit_limit = 50;
            $hit_counter = 0;
            $status = 1;
            

            $response = mysqli_query($conn, "INSERT INTO `api_token`(`nid`, `token`, `hit_limit`, `hit_counter`, `status`) VALUES ('$nid','$token', '$hit_limit', '$hit_counter','$status')");

            if($response){
                $res =  mysqli_query($conn, "SELECT *  FROM api_token WHERE nid = '$recordID' AND token = '$token'");
                $x = mysqli_fetch_assoc($res);
                $y = $x['token'];
                $z = $x['id'];
                $data = [
                    'status' => 200,
                    'message' => "Record Showed Successfully",
                    'record' => $y,
                    'token_id' => $z,
                ];
                header("HTTP/1.0 200 Success");
                return json_encode($data);
            }

            // $data = [
            //     'status' => 200,
            //     'message' => "Record Showed Successfully",
            //     'record' => $res,
            // ];
            // header("HTTP/1.0 200 Success");
            // return json_encode($data);

            
        }
        else{
            $data = [
                'status' => 404,
                'message' => "No Record Found",
            ];
            header("HTTP/1.0 404 No Record Found");
            return json_encode($data);
        }
    }
    else{
        $data = [
            'status' => 500,
            'message' => "Internal Server Error",
        ];
        header("HTTP/1.0 500 Internal Server Error");
        return json_encode($data);
    }


}


?>