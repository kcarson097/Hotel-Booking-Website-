<?php

include("../connections/conn.php");
header('Content-Type: application/json');
include("../apisecurity/authenticatekey.php");//checks if api key is valid

if($res['Valid'] == true){//valid key so api can run

    if(isset($_GET['propID'],$_GET['userID'])){
        $propID = $_GET['propID'];
        $userID = $_GET['userID'];

        $sql = "INSERT INTO webdevproject_favourites(PropertyID,AccountID) VALUES ($propID,$userID)";


        $result1 = $conn->query($sql);

        if(!$result1){
            echo $conn->error;
        }
    }
}else{
    echo "Invalid Api Key";
  }



?>