<?php

include("../connections/conn.php");
header('Content-Type: application/json');

include("../apisecurity/authenticatekey.php");//checks if api key is valid

if($res['Valid'] == true){//valid key so api can run

    //connects to database and returns all the ammeneties that the specified property has
    if(isset($_GET['propertyID'])){

        $propID = $_GET['propertyID'];

        $sql = "SELECT PropertyID, webdevproject_amenities.Name AS 'Amenity' 
        FROM webdevproject_propertyamenities1 
        INNER JOIN webdevproject_amenities
        ON webdevproject_amenities.AmenityID = webdevproject_propertyamenities1.AmenityID
        WHERE PropertyID = $propID";

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



