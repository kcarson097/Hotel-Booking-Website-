<?php

include("../connections/conn.php");
header('Content-Type: application/json');
include("../apisecurity/authenticatekey.php");//checks if api key is valid

if($res['Valid'] == true){//valid key so api can run

    //adds prop amens to the pro amens table
    $propID = $_GET['propID'];
    $amens = $_GET['amenity'];

    $sql = "INSERT INTO `webdevproject_propertyamenities1`(`PropertyAmenityId`,`AmenityID`, `PropertyID`) VALUES (NULL,$amens,$propID)";


    $result1 = $conn->query($sql);

    if(!$result1){
        echo $conn->error;
    }
}else{
    echo "Invalid Api Key";
  }