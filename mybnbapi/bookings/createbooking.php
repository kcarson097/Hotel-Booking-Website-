<?php

include("../connections/conn.php");
header('Content-Type: application/json');
include("../apisecurity/authenticatekey.php");//checks if api key is valid

if($res['Valid'] == true){//valid key so api can run

    //creates a booking for the user on property between the dates start and end
    if(isset($_GET['propID'],$_GET['startdate'],$_GET['enddate'],$_GET['userID'])){
        
        $propID = $_GET['propID'];
        $start = $_GET['startdate'];
        $end = $_GET['enddate'];
        $userID = $_GET['userID'];

        $sql = "INSERT INTO webdevproject_bookings(UserID, PropertyID, StartDate, EndDate) VALUES ($userID,$propID,$start,$end)";

        $result1 = $conn->query($sql);
        if(!$result1){
            echo $conn->error;
        }
    }
}else{
    echo "Invalid Api Key";
  }





?>