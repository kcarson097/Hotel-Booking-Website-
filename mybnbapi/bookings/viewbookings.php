<?php


include("../connections/conn.php");
header('Content-Type: application/json');
include("../apisecurity/authenticatekey.php");//checks if api key is valid

if($res['Valid'] == true){//valid key so api can run

    //connects to the db and finds all the bookings that the user has made 
    if(isset($_GET['userID'],$_GET['page'])){

        $userID = $_GET['userID'];
        $pageNumber = $_GET['page'];


        $sql = "SELECT webdevproject_bookings.StartDate,webdevproject_bookings.EndDate, webdevproject_properties.PropertyID,
        webdevproject_propertytypes.Name AS 'PropertyType',
        webdevproject_properties.Headline, webdevproject_properties.thumbnail_url, webdevproject_properties.Price,
        webdevproject_properties.Accomodates, webdevproject_properties.Rating, webdevproject_properties.NumberOfBeds,
        webdevproject_properties.NumberOfBedrooms,webdevproject_properties.NumberOfBathrooms,
        webdevproject_cancellationpolicies.Name AS 'CancellationPolicy',webdevproject_cities.Name As 'City'
        FROM webdevproject_bookings
        INNER JOIN webdevproject_properties
        ON webdevproject_properties.PropertyID = webdevproject_bookings.PropertyID
        INNER JOIN webdevproject_propertytypes 
        ON webdevproject_propertytypes.PropertyTypeID = webdevproject_properties.PropertyTypeID
        INNER JOIN webdevproject_cancellationpolicies
        ON webdevproject_properties.CancellationPolicyID = webdevproject_cancellationpolicies.PolicyID
        INNER JOIN webdevproject_cities
        ON webdevproject_cities.CityID = webdevproject_properties.CityID
        WHERE webdevproject_bookings.UserID =  $userID";

        $numberOfResults = 0;
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

        $sql1 = "SELECT webdevproject_bookings.StartDate,webdevproject_bookings.EndDate,
        webdevproject_properties.PropertyID,webdevproject_propertytypes.Name AS 'PropertyType',
        webdevproject_properties.Headline, webdevproject_properties.thumbnail_url, webdevproject_properties.Price,
        webdevproject_properties.Accomodates, webdevproject_properties.Rating, webdevproject_properties.NumberOfBeds,
        webdevproject_properties.NumberOfBedrooms,webdevproject_properties.NumberOfBathrooms,
        webdevproject_cancellationpolicies.Name AS 'CancellationPolicy',webdevproject_cities.Name As 'City'
        FROM webdevproject_bookings  
        INNER JOIN webdevproject_properties
        ON webdevproject_properties.PropertyID = webdevproject_bookings.PropertyID
        INNER JOIN webdevproject_propertytypes 
        ON webdevproject_propertytypes.PropertyTypeID = webdevproject_properties.PropertyTypeID
        INNER JOIN webdevproject_cancellationpolicies
        ON webdevproject_properties.CancellationPolicyID = webdevproject_cancellationpolicies.PolicyID
        INNER JOIN webdevproject_cities
        ON webdevproject_cities.CityID = webdevproject_properties.CityID
        WHERE webdevproject_bookings.UserID =  $userID
        LIMIT $currentPageFirstResult,$results_per_page";

        $result2 = $conn->query($sql1);

        if(!$result2){
            echo $conn->error;
        }else if(mysqli_num_rows($result2)==0){
            //echo "No results found";
        }else{
            while($row = $result2->fetch_assoc()){ 
            array_push($finalres,$row);
            }
        }


        echo json_encode($finalres);
        }
}else{
    echo "Invalid Api Key";
  }

?>