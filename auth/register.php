<?php
if($_SERVER['REQUEST_METHOD'] === 'POST')
{
    header('Content-Type: application/json');
//Receive the RAW post data.
    $content = trim(file_get_contents("php://input"));
//Attempt to decode the incoming RAW post data from JSON.
    $decoded = json_decode($content, true);
//If json_decode failed, the JSON is invalid.
    if(!is_array($decoded)){
        header("HTTP/1.1 400 Bad Request");
        $errorData=array("code"=>400,"msg"=> " Bad Request");
        $response=array(
            "isError"=> true,
            "error"=>$errorData );
        echo json_encode($response);
    }
    else
    {
        if(isset($decoded['userEmail']))
        {
            $email=$decoded['userEmail'];
            $userPassword=$decoded['userPassword'];
            $firstName=$decoded['firstName'];
            $lastName=$decoded['lastName'];
            $phone=$decoded['phone'];
            $userPassword=md5($userPassword);
            require_once("../dbConn.php");
            $result=mysqli_query($connection,"select * from users where email = '$email'");
            if($row=mysqli_fetch_array($result))
            {
                header("HTTP/1.1 400 Not Allowed");
                $userId =$row['user_id'];
                $isError = true;
                $errorMessage = "Fail";
            }
            else {
                header("HTTP/1.1 200 OK");
                $isError = false;
                $errorMessage = "Success";
                mysqli_query($connection,"INSERT INTO `users` (`full_name`, `email`, `password`, `first_name`, `last_name`, `create_profile`, `mobile`, `forgot_otp`, `is_mobile_verify`) VALUES ( '$firstName $lastName', '$email', '$userPassword', '$firstName', '$lastName', current_timestamp(), '$phone', '', '0')");
                $userId = mysqli_insert_id($connection);
            }
            $errorData=null;
            require_once "../utils/functions.php";
            $data = array("userId"=>$userId);
            echo manageResponse($isError, $errorMessage, $data);
        }
        else{
            header("HTTP/1.1 403 Invalid Request");
            $errorData=array("code"=>403,"msg"=> "Invalid Request");
            $response=array(
                "isError"=> true,
                "error"=>$errorData );
            echo json_encode($response);
        }

    }
}
else
{

    header("HTTP/1.1 401 unauthorized user");
    $errorData=array("code"=>401,"msg"=> "unauthorized user");
    $response=array(
        "isError"=> true,
        "error"=>$errorData );
    echo json_encode($response);
}

?>