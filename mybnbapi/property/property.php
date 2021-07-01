<?php

include("../connections/conn.php");
header('Content-Type: application/json');

include("../apisecurity/authenticatekey.php");//checks if api key is valid

if($res['Valid'] == true){//valid key so api can run

    //returns all the details for a property
    if(isset($_GET['propertyID'])){
        $propID = $_GET['propertyID'];

        $sql = "SELECT webdevproject_propertytypes.Name AS 'PropertyType',webdevproject_roomtypes.Name AS 'RoomType',Headline,Description,PropPageImage,NumberOfReviews,ZipCode,Price,Accomodates,Rating,NumberOfBeds,NumberOfBedrooms,
        NumberOfBathRooms,webdevproject_cancellationpolicies.Name AS 'CancellationPolicy',webdevproject_cities.Name As 'City',webdevproject_hosts.HostName, webdevproject_hosts.HostRating,
        webdevproject_hosts.ResponseRate,webdevproject_properties.thumbnail_url
        FROM webdevproject_properties
        INNER JOIN webdevproject_propertytypes 
        ON webdevproject_propertytypes.PropertyTypeID = webdevproject_properties.PropertyTypeID
        INNER JOIN webdevproject_cancellationpolicies
        ON webdevproject_properties.CancellationPolicyID = webdevproject_cancellationpolicies.PolicyID
        INNER JOIN webdevproject_cities
        ON webdevproject_cities.CityID = webdevproject_properties.CityID
        INNER JOIN webdevproject_roomtypes
        ON webdevproject_roomtypes.RoomTypeID = webdevproject_properties.RoomTypeID
        INNER JOIN webdevproject_hosts
        ON webdevproject_hosts.HostID = webdevproject_properties.HostID
        WHERE  PropertyID = $propID";

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