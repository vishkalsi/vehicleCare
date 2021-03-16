<?php 
function manageResponse ($isError, $errorMessage, $data)
{
    $response=array(
        "isError"=> $isError,
        "error"=>$errorMessage,
        "data"=> $data,
        "VERSION_CURRENT" => 2,
        "VERSION_FORCE_UPDATE" => 2 
    );
    return json_encode($response);
}