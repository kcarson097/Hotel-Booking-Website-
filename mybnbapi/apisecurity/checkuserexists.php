<?php
    include("../connections/conn.php");
    header('Content-Type: application/json');
    include("../apisecurity/authenticatekey.php");//checks if api key is valid

    if($res['Valid'] == true){//valid key so api can run

        //checks if username already has an api key
        if(isset($_GET['username'])){
            $username = $_GET['username'];

            $sql = "SELECT * FROM webdevproject_apikeys WHERE Username = '$username' ";

    
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
    }else{
        echo "Invalid Api Key";
      }





?>