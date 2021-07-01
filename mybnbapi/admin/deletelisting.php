<?php


include("../connections/conn.php");
header('Content-Type: application/json');
include("../apisecurity/authenticatekey.php");//checks if api key is valid

if($res['Valid'] == true){//valid key so api can run

    //deletes property listing and also all the prop amenities in the amenities table as well as any appearences in favourites/bookings table
    if(isset($_GET['propID'])){
        $propID = $_GET['propID'];


        //remove all the prop amenities in the propamenities table
        $sql2 = "DELETE FROM webdevproject_propertyamenities1 WHERE PropertyID = $propID";

        $result2 = $conn->query($sql2);
        if(!$result2){
            echo $conn->error;
        }

        //remove all appearences in prop favourites
        $sql3 = "DELETE FROM webdevproject_favourites WHERE PropertyID = $propID ";
        
        $result3 = $conn->query($sql3);
        if(!$result3){
            echo $conn->error;
        }

        //remove all appearences in bookings
        $sql4 = "DELETE FROM webdevproject_bookings WHERE PropertyID = $propID ";
        
        $result4 = $conn->query($sql4);
        if(!$result4){
            echo $conn->error;
        }
    

        $sql1 = "DELETE FROM webdevproject_properties WHERE PropertyID = $propID ";
        $result1 = $conn->query($sql1);
        if(!$result1){
            echo $conn->error;
        }
    }
}else{
    echo "Invalid Api Key";
  }


    ?>