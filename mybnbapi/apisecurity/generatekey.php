<?php

    include("../connections/conn.php");
    header('Content-Type: application/json');
    include("../apisecurity/authenticatekey.php");//checks if api key is valid

    if($res['Valid'] == true){//valid key so api can run

        //generates api key for username
        if(isset($_GET['username'], $_GET['email'])){
            $username = $_GET['username'];
            $email = $_GET['email'];
            $running = 1;
            while($running == 1){//while the key is not unique generate a key and query if it exists
                //taken from csc 7062 John Busch Week11B Lecture
                $key = implode('-', str_split(substr(strtolower(md5(microtime().rand(1000, 9999))), 0, 30), 6)); //generate a random key
                $sql = "SELECT * FROM webdevproject_apikeys WHERE APIKey = '$key' ";
                $numberOfResults = 0;
        
                $result1 = $conn->query($sql);
            
                if(!$result1){
                    echo $conn->error;
                }else{
                    while($row = $result1->fetch_assoc()){ 
                        $numberOfResults++;
                    }
                }
                if($numberOfResults == 0){//key hasnt been used before
                    $running == 0;
                    break;
                }
            }
            //insert details into db and also return the key value as a json response
            $res = array("Key"=>$key);
            $insert = "INSERT INTO webdevproject_apikeys(Username,Email,APIKey) VALUES ('$username','$email','$key')";
            $result1 = $conn->query($insert); 
            if(!$result1){
                echo $conn->error;
            }

            echo json_encode($res);

        }

}else{
    echo "Invalid Api Key";
  }







?>