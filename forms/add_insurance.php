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
        if(isset($decoded['registrationYear']))
        {
            $registrationYear=$decoded['registrationYear'];
            $state=$decoded['state'];
            $vehicleName=$decoded['vehicleName'];
            $modelName=$decoded['modelName'];
            $vehicleNumber=$decoded['vehicleNumber'];
            $startDate="01-".$decoded['startDate'];
            $endDate="01-".$decoded['endDate'];
            $userId=$decoded['userId'];
            $startDate = date("Y-m-d", strtotime($startDate));
            $endDate = date("Y-m-d", strtotime($endDate));
            require_once("../dbConn.php");
            mysqli_query($connection, "INSERT INTO `documents` (`doc_type`, `vehicle_name`, `model_name`, `registaration_year`, `registration_state`, `start_date`, `end_date`, `user_id`, vehicle_number) VALUES ( '1', '$vehicleName', '$modelName', '$registrationYear', '$state', '$startDate', '$endDate', '$userId','$vehicleNumber')"); 
            $errorData=null;
            require_once "../utils/functions.php";
            echo manageResponse(false, "", null);
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