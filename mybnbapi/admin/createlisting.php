<?php

include("../connections/conn.php");
header('Content-Type: application/json');
include("../apisecurity/authenticatekey.php");//checks if api key is valid

if($res['Valid'] == true){//valid key so api can run

    //get all of variables and then run insert quert to create prop listing

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



    //insert listing in properties table
    $sql = "INSERT INTO webdevproject_properties(PropertyTypeID, RoomTypeID, CityID, HostID, Headline, Description, 
                                Accomodates, NumberOfReviews, CancellationPolicyID,
                                Rating, ZipCode, Price, NumberOfBeds, NumberOfBedrooms, NumberOfBathRooms, 
                                thumbnail_url, PropPageImage, NumberOfLikes) 
                                    VALUES ($propType,$roomtype,$city,$host,$headline,$description,
                                    $accomodates,$reviews,$cancelpolicy,$rating,
                                    $zipcode,$price,$beds,$bedrooms,$bathrooms,$thumbnail,
                                    $mainimage,0)";

    $result1 = $conn->query($sql);
    if(!$result1){
        echo $conn->error;
    }else{
        $lastID = $conn ->insert_id; //gets the propertyid of the new listing
        $res = array("PropertyID"=>"$lastID");
    }

    echo json_encode($res);

}else{
    echo "Invalid Api Key";
  }