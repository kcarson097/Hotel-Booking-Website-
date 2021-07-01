<?php
    $passw = "j3K0yJ6ql19t9VHp";
       
    $username = "kcarson09";
 
    $db = "kcarson09";
 
    $host = "kcarson09.lampt.eeecs.qub.ac.uk";
 
    $conn = new mysqli($host, $username, $passw, $db);
 
    if($conn->error){
        echo "not connected".$conn->error;
    }else{
        //echo "connection to DB found.";
    }
 
?>