<?php

include("../connections/conn.php");
header('Content-Type: application/json');
include("../apisecurity/authenticatekey.php");//checks if api key is valid

if($res['Valid'] == true){//valid key so api can run

    //returns any bookings for the inputted property have dates that overlap with the proposed dates
    if(isset($_GET['propID'],$_GET['startdate'],$_GET['enddate'])){
        $propID = $_GET['propID'];
        $start = $_GET['startdate'];
        $end = $_GET['enddate'];

        $sql = "SELECT * 
        FROM webdevproject_bookings 
        WHERE ('$start'<=EndDate) 
        AND (StartDate<='$end') 
        AND PropertyID = $propID";

        $numberOfResults = 0;
        
        $result1 = $conn->query($sql);

        if(!$result1){
            echo $conn->error;
        }else{
            while($row = $result1->fetch_assoc()){ 
                $numberOfResults++;
            }
        }
        $finalres = array("Number of results"=>"$numberOfResults");
        $result2 = $conn->query($sql);

        while($row = $result2->fetch_assoc()){ 
            array_push($finalres,$row);
        }


        echo json_encode($finalres);

        }
    }
    else{
    echo "Invalid Api Key";
  }





?>