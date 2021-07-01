<?php

include("../connections/conn.php");
header('Content-Type: application/json');
include("../apisecurity/authenticatekey.php");//checks if api key is valid

if($res['Valid'] == true){//valid key so api can run

    //returns all the room types stored in db
    if(isset($_GET['roomtype'])){
    $sql = "SELECT * FROM webdevproject_roomtypes WHERE 1";

    $result = $conn->query($sql);
    $final = array();

    if(!$result){
        echo $conn->error;   
    }else{
        while($row = $result->fetch_assoc()){ 
        array_push($final,$row);
        }
    }
    echo json_encode($final);
    }

    //returns all hosts stored in db
    else if(isset($_GET['hosts'])){
        
        $sql = "SELECT * FROM webdevproject_hosts WHERE 1";

        $result = $conn->query($sql);
        $final = array();

        if(!$result){
            echo $conn->error;   
        }else{
            while($row = $result->fetch_assoc()){ 
            array_push($final,$row);
            }
        }
        echo json_encode($final);
        }
    //gets all cancel policies
    else if(isset($_GET['cancelpolicies'])){
        
        $sql = "SELECT * FROM webdevproject_cancellationpolicies WHERE 1";

        $result = $conn->query($sql);
        $final = array();

        if(!$result){
            echo $conn->error;   
        }else{
            while($row = $result->fetch_assoc()){ 
            array_push($final,$row);
            }
        }
        echo json_encode($final);
        }
}else{
    echo "Invalid Api Key";
  }

?>
