<?php

include("../connections/conn.php");
header('Content-Type: application/json');
include("../apisecurity/authenticatekey.php");//checks if api key is valid

    if($res['Valid'] == true){//valid key so api can run

    $propID = $_GET['propID'];

    $sql = "DELETE FROM webdevproject_propertyamenities1  WHERE PropertyID = $propID";


    $result1 = $conn->query($sql);

    if(!$result1){
        echo $conn->error;
    }
}else{
    echo "Invalid Api Key";
  }


?>