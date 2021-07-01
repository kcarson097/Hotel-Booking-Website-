<?php

include("../connections/conn.php");
header('Content-Type: application/json');
include("../apisecurity/authenticatekey.php");//checks if api key is valid

if($res['Valid'] == true){//valid key so api can run

    //this will be called when a user selects one of the property types from the home page
    if(isset($_GET['propertytype'],$_GET['page'])){
        $propTypeID = $_GET['propertytype']; //this will be an array
        $pageNumber = $_GET['page'];

        $numberOfResults = 0;

        $sql = "SELECT PropertyID,webdevproject_propertytypes.Name AS 'PropertyType',Headline,thumbnail_url,Price,Accomodates,Rating,NumberOfBeds,NumberOfBedrooms,
        NumberOfBathRooms,webdevproject_cancellationpolicies.Name AS 'CancellationPolicy',webdevproject_cities.Name As 'City'
        FROM webdevproject_properties
        INNER JOIN webdevproject_propertytypes 
        ON webdevproject_propertytypes.PropertyTypeID = webdevproject_properties.PropertyTypeID
        INNER JOIN webdevproject_cancellationpolicies
        ON webdevproject_properties.CancellationPolicyID = webdevproject_cancellationpolicies.PolicyID
        INNER JOIN webdevproject_cities
        ON webdevproject_cities.CityID = webdevproject_properties.CityID
        WHERE  ";

        //loop thru array and add to sql statement
        $arraySize = count($propTypeID);
        for($i=0;$i<$arraySize-1;$i++){
            $sql .= "webdevproject_properties.PropertyTypeID =$propTypeID[$i] OR ";
        }
        $arraySize -= 1;
        $sql .= "webdevproject_properties.PropertyTypeID =$propTypeID[$arraySize]";
        
        $result1 = $conn->query($sql);

        if(!$result1){
            echo $conn->error;
        }else if(mysqli_num_rows($result1)==0){
            echo "No results found";
        }else{
            while($row = $result1->fetch_assoc()){ 
                $numberOfResults++;
            }
        }

        $finalres = array("Number of results"=>"$numberOfResults");
        //echo json_encode($finalres);


        //define how many results to display per page
        $results_per_page = 20;

        //determine the sql LIMIT starting number for the results on the current page
        $currentPageFirstResult = ($pageNumber-1)*$results_per_page;

        $sql = "SELECT PropertyID,webdevproject_propertytypes.Name AS 'PropertyType',Headline,thumbnail_url,Price,Accomodates,Rating,NumberOfBeds,NumberOfBedrooms,
        NumberOfBathRooms,webdevproject_cancellationpolicies.Name AS 'CancellationPolicy',webdevproject_cities.Name As 'City'
        FROM webdevproject_properties
        INNER JOIN webdevproject_propertytypes 
        ON webdevproject_propertytypes.PropertyTypeID = webdevproject_properties.PropertyTypeID
        INNER JOIN webdevproject_cancellationpolicies
        ON webdevproject_properties.CancellationPolicyID = webdevproject_cancellationpolicies.PolicyID
        INNER JOIN webdevproject_cities
        ON webdevproject_cities.CityID = webdevproject_properties.CityID
        WHERE  ";

        //loop thru array and add to sql statement
        $arraySize = count($propTypeID);
        for($i=0;$i<$arraySize-1;$i++){
            $sql .= "webdevproject_properties.PropertyTypeID =$propTypeID[$i] OR ";
        }
        $arraySize -= 1;
        $sql .= "webdevproject_properties.PropertyTypeID =$propTypeID[$arraySize] LIMIT $currentPageFirstResult,$results_per_page";
        
        $result = $conn->query($sql);

        if(!$result){
            echo $conn->error;
        }else if(mysqli_num_rows($result)==0){
            echo "No results found";
        }else{
            while($row = $result->fetch_assoc()){ 
            array_push($finalres,$row);
            }
        }
        echo json_encode($finalres);
        }//this is called to generate the filter options - returns all the property types
        else if(isset($_GET['propertytype'])){
        $sql = "SELECT * FROM webdevproject_propertytypes WHERE 1";

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