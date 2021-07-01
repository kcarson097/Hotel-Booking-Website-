<?php


include("../connections/conn.php");
header('Content-Type: application/json');
include("../apisecurity/authenticatekey.php");//checks if api key is valid

if($res['Valid'] == true){//valid key so api can run

    //deletes favourite entry from propfavs table and also reduces number of likes in the main properties table
    if(isset($_GET['propID'],$_GET['userID'])){
        $propID = $_GET['propID'];
        $userID = $_GET['userID'];


        $sql = "DELETE FROM webdevproject_favourites WHERE AccountID=$userID AND PropertyID = $propID ";
        $result1 = $conn->query($sql);
        if(!$result1){
            echo $conn->error;
        }
        //decrement number of likes
        $sql = "UPDATE webdevproject_properties SET NumberOfLikes= (NumberOfLikes-1) WHERE PropertyID = $propID";

        $result1 = $conn->query($sql);
        if(!$result1){
            echo $conn->error;
        }
    }
}else{
    echo "Invalid Api Key";
  }
?>