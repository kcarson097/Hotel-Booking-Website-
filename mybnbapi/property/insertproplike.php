<?php

include("../connections/conn.php");
header('Content-Type: application/json');

include("../apisecurity/authenticatekey.php");//checks if api key is valid

if($res['Valid'] == true){//valid key so api can run

    //increments the number of likes column for the relevant property when a user likes it
    if(isset($_GET['propID'])){
        $propID = $_GET['propID'];

        $sql = "UPDATE webdevproject_properties SET NumberOfLikes= (NumberOfLikes+1) WHERE PropertyID = $propID";

        $result1 = $conn->query($sql);
        if(!$result1){
            echo $conn->error;
        }

    }
}else{
    echo "Invalid Api Key";
}





?>




