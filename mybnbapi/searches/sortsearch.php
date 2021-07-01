<?php

include("../connections/conn.php");
header('Content-Type: application/json');
include("../apisecurity/authenticatekey.php");//checks if api key is valid

if($res['Valid'] == true){//valid key so api can run

    //when the user selects to sort the results from a default search
    if(isset($_GET['location'],$_GET['accomodates'],$_GET['page'],$_GET['sortby'])){
        $cityID = $_GET['location'];
        $accomodates = $_GET['accomodates'];
        $pageNumber = $_GET['page'];
        $sortby = $_GET['sortby'];

        if($sortby=="Price"){
            $by = " ASC";
        }else{
            $by = " DESC";
        }


        $numberOfResults = 0;
        //get the total number of results first - this is needed to generate pagination bar
        $sql = "SELECT PropertyID,webdevproject_propertytypes.Name AS 'PropertyType',Headline,thumbnail_url,Price,Accomodates,Rating,NumberOfBeds,NumberOfBedrooms,
        NumberOfBathRooms,webdevproject_cancellationpolicies.Name AS 'CancellationPolicy',webdevproject_cities.Name As 'City'
        FROM webdevproject_properties
        INNER JOIN webdevproject_propertytypes 
        ON webdevproject_propertytypes.PropertyTypeID = webdevproject_properties.PropertyTypeID
        INNER JOIN webdevproject_cancellationpolicies
        ON webdevproject_properties.CancellationPolicyID = webdevproject_cancellationpolicies.PolicyID
        INNER JOIN webdevproject_cities
        ON webdevproject_cities.CityID = webdevproject_properties.CityID
        WHERE webdevproject_properties.CityID = $cityID AND Accomodates >= $accomodates
        ORDER BY webdevproject_properties.$sortby $by";
        

        $result1 = $conn->query($sql);

        if(!$result1){
            echo $conn->error;
        }else if(mysqli_num_rows($result1)==0){
            //echo "No results found";
        }else{
            while($row = $result1->fetch_assoc()){ 
                $numberOfResults++;
            }
        }

        $finalres = array("Number of results"=>"$numberOfResults");

        //define how many results to display per page
        $results_per_page = 20;

        //determine the sql LIMIT starting number for the results on the current page
        $currentPageFirstResult = ($pageNumber-1)*$results_per_page;
    
        //run the query for the specified page in the url
        $sql1 = "SELECT PropertyID,webdevproject_propertytypes.Name AS 'PropertyType',Headline,thumbnail_url,Price,Accomodates,Rating,NumberOfBeds,NumberOfBedrooms,
        NumberOfBathRooms,webdevproject_cancellationpolicies.Name AS 'CancellationPolicy',webdevproject_cities.Name As 'City'
        FROM webdevproject_properties
        INNER JOIN webdevproject_propertytypes 
        ON webdevproject_propertytypes.PropertyTypeID = webdevproject_properties.PropertyTypeID
        INNER JOIN webdevproject_cancellationpolicies
        ON webdevproject_properties.CancellationPolicyID = webdevproject_cancellationpolicies.PolicyID
        INNER JOIN webdevproject_cities
        ON webdevproject_cities.CityID = webdevproject_properties.CityID
        WHERE webdevproject_properties.CityID = $cityID AND Accomodates >= $accomodates
        ORDER BY webdevproject_properties.$sortby $by 
        LIMIT $currentPageFirstResult,$results_per_page"; //i.e show results FirstResult to firstresult + results per page
        
        $result = $conn->query($sql1);
        
        if(!$result){
            echo $conn->error;
        }else if(mysqli_num_rows($result)==0){
            //echo "No results found";
        }else{
            while($row = $result->fetch_assoc()){ 
                array_push($finalres,$row);
            }
        }
        echo json_encode($finalres);
        }
        
        
        ///////////////////////////////////////////////////////////////////////
        else if(isset($_GET['propertytype'],$_GET['sortby'],$_GET['page'])){
            $sortby = $_GET['sortby'];
            $propTypes = $_GET['propertytype']; //this will be an array !
            $pageNumber = $_GET['page'];

            $numberOfResults = 0;

            if($sortby=="Price"){
                $by = " ASC";
            }else{
                $by = " DESC";
            }
        
            //get the total number of results first - this is needed to generate pagination bar
            $sql = "SELECT PropertyID,webdevproject_propertytypes.Name AS 'PropertyType',Headline,thumbnail_url,Price,Accomodates,Rating,NumberOfBeds,NumberOfBedrooms,
            NumberOfBathRooms,webdevproject_cancellationpolicies.Name AS 'CancellationPolicy',webdevproject_cities.Name As 'City'
            FROM webdevproject_properties
            INNER JOIN webdevproject_propertytypes 
            ON webdevproject_propertytypes.PropertyTypeID = webdevproject_properties.PropertyTypeID
            INNER JOIN webdevproject_cancellationpolicies
            ON webdevproject_properties.CancellationPolicyID = webdevproject_cancellationpolicies.PolicyID
            INNER JOIN webdevproject_cities
            ON webdevproject_cities.CityID = webdevproject_properties.CityID
            WHERE ";

            //loop thru array and add to sql statement
            $arraySize = count($propTypes);
            for($i=0;$i<$arraySize-1;$i++){
                $sql .= "webdevproject_properties.PropertyTypeID =$propTypes[$i] OR ";
            }
            $arraySize -= 1;
            $sql .= "webdevproject_properties.PropertyTypeID =$propTypes[$arraySize] ORDER BY webdevproject_properties.$sortby $by ";

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

            //define how many results to display per page
            $results_per_page = 20;

            //determine the sql LIMIT starting number for the results on the current page
            $currentPageFirstResult = ($pageNumber-1)*$results_per_page;


            $sql1 = "SELECT PropertyID,webdevproject_propertytypes.Name AS 'PropertyType',Headline,thumbnail_url,Price,Accomodates,Rating,NumberOfBeds,NumberOfBedrooms,
            NumberOfBathRooms,webdevproject_cancellationpolicies.Name AS 'CancellationPolicy',webdevproject_cities.Name As 'City'
            FROM webdevproject_properties
            INNER JOIN webdevproject_propertytypes 
            ON webdevproject_propertytypes.PropertyTypeID = webdevproject_properties.PropertyTypeID
            INNER JOIN webdevproject_cancellationpolicies
            ON webdevproject_properties.CancellationPolicyID = webdevproject_cancellationpolicies.PolicyID
            INNER JOIN webdevproject_cities
            ON webdevproject_cities.CityID = webdevproject_properties.CityID
            WHERE "; //i.e show results FirstResult to firstresult + results per page

            //loop thru array and add to sql statement
            $arraySize = count($propTypes);
            for($i=0;$i<$arraySize-1;$i++){
                $sql .= "webdevproject_properties.PropertyTypeID =$propTypes[$i] OR ";
            }
            $arraySize -= 1;
            $sql1 .= "webdevproject_properties.PropertyTypeID =$propTypes[$arraySize] ORDER BY webdevproject_properties.$sortby $by LIMIT $currentPageFirstResult,$results_per_page ";
            
            $result = $conn->query($sql1);
        
            if(!$result){
                echo $conn->error;
            }else if(mysqli_num_rows($result)==0){
                //echo "No results found";
            }else{
                while($row = $result->fetch_assoc()){ 
                    array_push($finalres,$row);
                }
                }
                echo json_encode($finalres);
            }
    }else{
        echo "Invalid Api Key";
    }








?>