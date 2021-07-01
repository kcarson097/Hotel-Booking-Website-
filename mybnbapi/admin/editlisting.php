<?php

include("../connections/conn.php");
header('Content-Type: application/json');
include("../apisecurity/authenticatekey.php");//checks if api key is valid

if($res['Valid'] == true){//valid key so api can run

    //updates the property listing and its amenities
    $propID = $_GET['propID'];

    //get all of variables and then run update query on listing
    $propType = $_GET['propType'];
    $roomtype = $_GET['roomtype'];
    $city = $_GET['city'];
    $host = $_GET['host'];
    $headline = $_GET['headline'];
    $description = $_GET['description'];
    $accomodates = $_GET['accomodates'];
    $reviews = $_GET['reviews'];
    $cancelpolicy = $_GET['cancelpolicy'];
    $rating = $_GET['rating'];
    $zipcode = $_GET['zipcode'];
    $price = $_GET['price'];
    $beds = $_GET['beds'];
    $bedrooms = $_GET['bedrooms'];
    $bathrooms = $_GET['bathrooms'];
    $thumbnail = $_GET['thumbnail'];
    $mainimage = $_GET['proppageimage'];

    $sql1 = "UPDATE webdevproject_properties 
    SET PropertyTypeID=$propType,
    RoomTypeID=$roomtype,CityID=$city,HostID=$host,
    Headline=$headline,Description=$description,Accomodates=$accomodates, NumberOfReviews =$reviews, CancellationPolicyID =$cancelpolicy, Rating =$rating, ZipCode =$zipcode,
    Price =$price, NumberOfBeds =$beds, NumberOfBedrooms =$bedrooms,
    NumberOfBathRooms =$bathrooms,
    thumbnail_url =$thumbnail, PropPageImage = $mainimage 
    WHERE PropertyID = $propID";

    $result1 = $conn->query($sql1);
    if(!$result1){
        echo $conn->error;
    }

}else{
    echo "Invalid Api Key";
  }






?>