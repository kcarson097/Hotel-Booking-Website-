<?php

include("../connections/conn.php");
header('Content-Type: application/json');

include("../apisecurity/authenticatekey.php");//checks if api key is valid

if($res['Valid'] == true){//valid key so api can run

  if(isset($_GET['amenities'])){
  
    
    $sql = "SELECT * FROM webdevproject_amenities WHERE 1 ";
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