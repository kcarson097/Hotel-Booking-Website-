<?php


include("../connections/conn.php");
header('Content-Type: application/json');

//verifies that the api key is valid

$key = $_GET['key'];

$sql = "SELECT * FROM webdevproject_apikeys WHERE APIKey = $key";
$result1 = $conn->query($sql);

$numofrows = $result1->num_rows;

if($numofrows>0){//i.e the key is in the db
    $res = array("Valid"=>true);
}else{
    $res = array("Valid"=>false);
}

  
//echo json_encode($res);
