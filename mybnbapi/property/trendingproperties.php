<?php

    include("../connections/conn.php");
    header('Content-Type: application/json');
    include("../apisecurity/authenticatekey.php");//checks if api key is valid

    if($res['Valid'] == true){//valid key so api can run

        //gets the top 5 liked properties in the city
        if(isset($_GET['cityID'])){
            $cityID = $_GET['cityID'];

            $sql = "SELECT webdevproject_properties.PropertyID,webdevproject_propertytypes.Name AS 'PropertyType',webdevproject_roomtypes.Name AS 'RoomType',Headline,Description,thumbnail_url,NumberOfReviews,ZipCode,Price,Accomodates,Rating,NumberOfBeds,NumberOfBedrooms,
            NumberOfBathRooms,webdevproject_cancellationpolicies.Name AS 'CancellationPolicy',webdevproject_cities.Name As 'City',webdevproject_hosts.HostName, webdevproject_hosts.HostRating,
            webdevproject_hosts.ResponseRate,webdevproject_properties.NumberOfLikes
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
            WHERE  webdevproject_properties.CityID = $cityID
            ORDER BY NumberOfLikes DESC";

            $result = $conn->query($sql);
            $final = array();
            $count = 0;

        
            if(!$result){
                echo $conn->error;   
            }else{
                while(($row = $result->fetch_assoc()) && ($count <5)){ 
                array_push($final,$row);
                $count++;
                }
            }
            echo json_encode($final);
            }
        else{//returns top 5 properties overall in db
            
            $sql = "SELECT webdevproject_properties.PropertyID,webdevproject_propertytypes.Name AS 'PropertyType',webdevproject_roomtypes.Name AS 'RoomType',Headline,Description,thumbnail_url,NumberOfReviews,ZipCode,Price,Accomodates,Rating,NumberOfBeds,NumberOfBedrooms,
            NumberOfBathRooms,webdevproject_cancellationpolicies.Name AS 'CancellationPolicy',webdevproject_cities.Name As 'City',webdevproject_hosts.HostName, webdevproject_hosts.HostRating,
            webdevproject_hosts.ResponseRate,webdevproject_properties.NumberOfLikes
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
            ORDER BY NumberOfLikes DESC";

            $result = $conn->query($sql);
            $final = array();
            $count = 0;

        
            if(!$result){
                echo $conn->error;   
            }else{
                while(($row = $result->fetch_assoc()) && ($count <5)){ 
                array_push($final,$row);
                $count++;
                }
            }
            echo json_encode($final);


        }
}else{
    echo "Invalid Api Key";
}
    



?>