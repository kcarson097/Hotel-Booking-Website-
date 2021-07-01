<?php

include("../connections/conn.php");
header('Content-Type: application/json');
include("../apisecurity/authenticatekey.php");//checks if api key is valid

if($res['Valid'] == true){//valid key so api can run


    //checks if login credientials match any results in the db
    if(isset($_GET['username'],$_GET['password'])){
        $username = $_GET['username'];
        $password = $_GET['password'];

        $sql = "SELECT * FROM webdevproject_useraccounts WHERE Username = '$username' AND Password = MD5('$password')";

    
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
    //checks if username already exists
    else if(isset($_GET['username'])){
        $username = $_GET['username'];
    
        $sql = "SELECT * FROM webdevproject_useraccounts WHERE Username = '$username'";

        $numberOfResults = 0;
        $result1 = $conn->query($sql);

        if(!$result1){
            echo $conn->error;
        }else if(mysqli_num_rows($result1)==0){
            //echo "No results found";
        }else{
            while($row = $result1->fetch_assoc()){ 
                $numberOfResults++;
            }
        }

        $finalres = array("Number of results"=>"$numberOfResults");
        echo json_encode($finalres);
    }
}else{
    echo "Invalid Api Key";
  }



?>