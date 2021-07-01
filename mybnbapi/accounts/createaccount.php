<?php

include("../connections/conn.php");
header('Content-Type: application/json');
include("../apisecurity/authenticatekey.php");//checks if api key is valid

if($res['Valid'] == true){//valid key so api can run

    //creates new user account, the account type is automatically set to a user type - admin roles would be creates by the system owner manually 
    if(isset($_GET['firstname'],$_GET['lastname'],$_GET['username'],$_GET['password'])){

        $firstname = $_GET['firstname'];
        $lastname = $_GET['lastname'];
        $username = $_GET['username'];
        $password = $_GET['password'];

        $sql = "INSERT INTO webdevproject_useraccounts (AccountType,FirstName, LastName,Username, Password) VALUES ('1','$firstname', '$lastname', '$username', MD5('$password'))";
        $result1 = $conn->query($sql);

        if(!$result1){
            echo $conn->error;
        }else{
            $lastID = $conn ->insert_id; //gets the propertyid of the new listing
            $res = array("UserID"=>"$lastID");
        }
        echo json_encode($res);
    }
}else{
    echo "Invalid Api Key";
  }





?>