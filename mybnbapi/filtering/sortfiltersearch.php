<?php


    include("../connections/conn.php");
    header('Content-Type: application/json');

    include("../apisecurity/authenticatekey.php");//checks if api key is valid

    if($res['Valid'] == true){//valid key so api can run

        //this will return all the results when the user selects all filter options from a search result
        if(isset($_GET['location'],$_GET['accomodates'],$_GET['page'],$_GET['proptype'],$_GET['cancelpolicy'],$_GET['amenities'])){

            $city = $_GET['location'];
            $guests = $_GET['accomodates'];
            $pageNumber = $_GET['page'];
            $proptype = $_GET['proptype']; //array
            $cancelpolicy = $_GET['cancelpolicy']; //array
            $amenities = $_GET['amenities']; //array
            $sortby = $_GET['sortby'];

            
            if($sortby=="Price"){
                $by = " ASC";
            }else{
                $by = " DESC";
            }

            


            $numberOfResults = 0;

            $sql = "SELECT DISTINCT webdevproject_properties.PropertyID,webdevproject_propertytypes.Name AS 'PropertyType',Headline,thumbnail_url,Price,Accomodates,Rating,NumberOfBeds,NumberOfBedrooms,NumberOfBathRooms,
            webdevproject_cancellationpolicies.Name AS 'CancellationPolicy',webdevproject_cities.Name As 'City'
            FROM webdevproject_properties
            INNER JOIN webdevproject_propertytypes 
            ON webdevproject_propertytypes.PropertyTypeID = webdevproject_properties.PropertyTypeID
            INNER JOIN webdevproject_cancellationpolicies
            ON webdevproject_properties.CancellationPolicyID = webdevproject_cancellationpolicies.PolicyID
            INNER JOIN webdevproject_cities
            ON webdevproject_cities.CityID = webdevproject_properties.CityID
            INNER JOIN webdevproject_propertyamenities1
            ON webdevproject_properties.PropertyID = webdevproject_propertyamenities1.PropertyID
            INNER JOIN webdevproject_amenities
            ON webdevproject_propertyamenities1.AmenityID = webdevproject_amenities.AmenityID
            WHERE webdevproject_properties.CityID = $city AND Accomodates >= $guests AND (( ";


            //loop thru proptype array and add to sql statement
            $arraySize = count($proptype);
            for($i=0;$i<$arraySize-1;$i++){
                $sql .= "webdevproject_properties.PropertyTypeID =$proptype[$i] OR ";
            }
            $arraySize -= 1;
            $sql .= "webdevproject_properties.PropertyTypeID =$proptype[$arraySize]) AND (";
        
            //loop thru cancel policy array and add to sql statement
            $arraySize = count($cancelpolicy);
            for($i=0;$i<$arraySize-1;$i++){
                $sql .= "webdevproject_cancellationpolicies.PolicyID =$cancelpolicy[$i] OR ";
            }
            $arraySize -= 1;
            $sql .= "webdevproject_cancellationpolicies.PolicyID =$cancelpolicy[$arraySize]) AND (";

            //loop thru amenity policy array and add to sql statement
            $arraySize = count($amenities);
            for($i=0;$i<$arraySize-1;$i++){
                $sql .= "webdevproject_amenities.AmenityID =$amenities[$i] OR ";
            }
            $arraySize -= 1;
            $sql .= "webdevproject_amenities.AmenityID =$amenities[$arraySize])) ";
            $sql .= "ORDER BY webdevproject_properties.$sortby $by ";

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
            //echo json_encode($finalres);
        
        
            //define how many results to display per page
            $results_per_page = 20;
        
            //determine the sql LIMIT starting number for the results on the current page
            $currentPageFirstResult = ($pageNumber-1)*$results_per_page;

            $sql .= "LIMIT $currentPageFirstResult,$results_per_page";

            $result = $conn->query($sql);

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
            



            else if(isset($_GET['location'],$_GET['accomodates'],$_GET['page'],$_GET['proptype'],$_GET['cancelpolicy'])){

                $city = $_GET['location'];
                $guests = $_GET['accomodates'];
                $pageNumber = $_GET['page'];
                $proptype = $_GET['proptype']; //array
                $cancelpolicy = $_GET['cancelpolicy']; //array

                $sortby = $_GET['sortby'];

            
                if($sortby=="Price"){
                    $by = " ASC";
                }else{
                    $by = " DESC";
                }
        
                $numberOfResults = 0;
        
                $sql = "SELECT DISTINCT webdevproject_properties.PropertyID,webdevproject_propertytypes.Name AS 'PropertyType',Headline,thumbnail_url,Price,Accomodates,Rating,NumberOfBeds,NumberOfBedrooms,NumberOfBathRooms,
                webdevproject_cancellationpolicies.Name AS 'CancellationPolicy',webdevproject_cities.Name As 'City'
                FROM webdevproject_properties
                INNER JOIN webdevproject_propertytypes 
                ON webdevproject_propertytypes.PropertyTypeID = webdevproject_properties.PropertyTypeID
                INNER JOIN webdevproject_cancellationpolicies
                ON webdevproject_properties.CancellationPolicyID = webdevproject_cancellationpolicies.PolicyID
                INNER JOIN webdevproject_cities
                ON webdevproject_cities.CityID = webdevproject_properties.CityID
                INNER JOIN webdevproject_propertyamenities1
                ON webdevproject_properties.PropertyID = webdevproject_propertyamenities1.PropertyID
                INNER JOIN webdevproject_amenities
                ON webdevproject_propertyamenities1.AmenityID = webdevproject_amenities.AmenityID
                WHERE webdevproject_properties.CityID = $city AND Accomodates >= $guests AND (( ";
        
        
                //loop thru proptype array and add to sql statement
                $arraySize = count($proptype);
                for($i=0;$i<$arraySize-1;$i++){
                    $sql .= "webdevproject_properties.PropertyTypeID =$proptype[$i] OR ";
                }
                $arraySize -= 1;
                $sql .= "webdevproject_properties.PropertyTypeID =$proptype[$arraySize]) AND (";
            
                //loop thru cancel policy array and add to sql statement
                $arraySize = count($cancelpolicy);
                for($i=0;$i<$arraySize-1;$i++){
                    $sql .= "webdevproject_cancellationpolicies.PolicyID =$cancelpolicy[$i] OR ";
                }
                $arraySize -= 1;
                $sql .= "webdevproject_cancellationpolicies.PolicyID =$cancelpolicy[$arraySize])) ";
                $sql .= "ORDER BY webdevproject_properties.$sortby $by ";
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
                //echo json_encode($finalres);
            
            
                //define how many results to display per page
                $results_per_page = 20;
            
                //determine the sql LIMIT starting number for the results on the current page
                $currentPageFirstResult = ($pageNumber-1)*$results_per_page;
        
                $sql .= "LIMIT $currentPageFirstResult,$results_per_page";
        
                $result = $conn->query($sql);
        
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

            


            else if(isset($_GET['location'],$_GET['accomodates'],$_GET['page'],$_GET['proptype'],$_GET['amenities'])){
                    
                $city = $_GET['location'];
                $guests = $_GET['accomodates'];
                $pageNumber = $_GET['page'];
                $proptype = $_GET['proptype']; //array
                $amenities = $_GET['amenities']; //array

                $sortby = $_GET['sortby'];

            
                if($sortby=="Price"){
                    $by = " ASC";
                }else{
                    $by = " DESC";
                }

                $numberOfResults = 0;

                $sql = "SELECT DISTINCT webdevproject_properties.PropertyID,webdevproject_propertytypes.Name AS 'PropertyType',Headline,thumbnail_url,Price,Accomodates,Rating,NumberOfBeds,NumberOfBedrooms,NumberOfBathRooms,
                webdevproject_cancellationpolicies.Name AS 'CancellationPolicy',webdevproject_cities.Name As 'City'
                FROM webdevproject_properties
                INNER JOIN webdevproject_propertytypes 
                ON webdevproject_propertytypes.PropertyTypeID = webdevproject_properties.PropertyTypeID
                INNER JOIN webdevproject_cancellationpolicies
                ON webdevproject_properties.CancellationPolicyID = webdevproject_cancellationpolicies.PolicyID
                INNER JOIN webdevproject_cities
                ON webdevproject_cities.CityID = webdevproject_properties.CityID
                INNER JOIN webdevproject_propertyamenities1
                ON webdevproject_properties.PropertyID = webdevproject_propertyamenities1.PropertyID
                INNER JOIN webdevproject_amenities
                ON webdevproject_propertyamenities1.AmenityID = webdevproject_amenities.AmenityID
                WHERE webdevproject_properties.CityID = $city AND Accomodates >= $guests AND (( ";


                //loop thru proptype array and add to sql statement
                $arraySize = count($proptype);
                for($i=0;$i<$arraySize-1;$i++){
                    $sql .= "webdevproject_properties.PropertyTypeID =$proptype[$i] OR ";
                }
                $arraySize -= 1;
                $sql .= "webdevproject_properties.PropertyTypeID =$proptype[$arraySize]) AND (";
            
                //loop thru amenity policy array and add to sql statement
                $arraySize = count($amenities);
                for($i=0;$i<$arraySize-1;$i++){
                    $sql .= "webdevproject_amenities.AmenityID =$amenities[$i] OR ";
                }
                $arraySize -= 1;
                $sql .= "webdevproject_amenities.AmenityID =$amenities[$arraySize])) ";
                $sql .= "ORDER BY webdevproject_properties.$sortby $by ";
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
                //echo json_encode($finalres);
            
            
                //define how many results to display per page
                $results_per_page = 20;
            
                //determine the sql LIMIT starting number for the results on the current page
                $currentPageFirstResult = ($pageNumber-1)*$results_per_page;

                $sql .= "LIMIT $currentPageFirstResult,$results_per_page";

                $result = $conn->query($sql);

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


                else if(isset($_GET['location'],$_GET['accomodates'],$_GET['page'],$_GET['cancelpolicy'],$_GET['amenities'])){
                    $city = $_GET['location'];
                    $guests = $_GET['accomodates'];
                    $pageNumber = $_GET['page'];
                    $cancelpolicy = $_GET['cancelpolicy']; //array
                    $amenities = $_GET['amenities']; //array

                    $sortby = $_GET['sortby'];

            
                    if($sortby=="Price"){
                        $by = " ASC";
                    }else{
                        $by = " DESC";
                    }
            
                    $numberOfResults = 0;
            
                    $sql = "SELECT DISTINCT webdevproject_properties.PropertyID,webdevproject_propertytypes.Name AS 'PropertyType',Headline,thumbnail_url,Price,Accomodates,Rating,NumberOfBeds,NumberOfBedrooms,NumberOfBathRooms,
                    webdevproject_cancellationpolicies.Name AS 'CancellationPolicy',webdevproject_cities.Name As 'City'
                    FROM webdevproject_properties
                    INNER JOIN webdevproject_propertytypes 
                    ON webdevproject_propertytypes.PropertyTypeID = webdevproject_properties.PropertyTypeID
                    INNER JOIN webdevproject_cancellationpolicies
                    ON webdevproject_properties.CancellationPolicyID = webdevproject_cancellationpolicies.PolicyID
                    INNER JOIN webdevproject_cities
                    ON webdevproject_cities.CityID = webdevproject_properties.CityID
                    INNER JOIN webdevproject_propertyamenities1
                    ON webdevproject_properties.PropertyID = webdevproject_propertyamenities1.PropertyID
                    INNER JOIN webdevproject_amenities
                    ON webdevproject_propertyamenities1.AmenityID = webdevproject_amenities.AmenityID
                    WHERE webdevproject_properties.CityID = $city AND Accomodates >= $guests AND (( ";
            
            
                
                    //loop thru cancel policy array and add to sql statement
                    $arraySize = count($cancelpolicy);
                    for($i=0;$i<$arraySize-1;$i++){
                        $sql .= "webdevproject_cancellationpolicies.PolicyID =$cancelpolicy[$i] OR ";
                    }
                    $arraySize -= 1;
                    $sql .= "webdevproject_cancellationpolicies.PolicyID =$cancelpolicy[$arraySize]) AND (";
            
                    //loop thru amenity policy array and add to sql statement
                    $arraySize = count($amenities);
                    for($i=0;$i<$arraySize-1;$i++){
                        $sql .= "webdevproject_amenities.AmenityID =$amenities[$i] OR ";
                    }
                    $arraySize -= 1;
                    $sql .= "webdevproject_amenities.AmenityID =$amenities[$arraySize])) ";
                    $sql .= "ORDER BY webdevproject_properties.$sortby $by ";
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
                    //echo json_encode($finalres);
                
                
                    //define how many results to display per page
                    $results_per_page = 20;
                
                    //determine the sql LIMIT starting number for the results on the current page
                    $currentPageFirstResult = ($pageNumber-1)*$results_per_page;
            
                    $sql .= "LIMIT $currentPageFirstResult,$results_per_page";
            
                    $result = $conn->query($sql);
            
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

                else if(isset($_GET['location'],$_GET['accomodates'],$_GET['page'],$_GET['proptype'])){

                    $city = $_GET['location'];
                    $guests = $_GET['accomodates'];
                    $pageNumber = $_GET['page'];
                    $proptype = $_GET['proptype']; //array

                    $sortby = $_GET['sortby'];

            
                    if($sortby=="Price"){
                        $by = " ASC";
                    }else{
                        $by = " DESC";
                    }

                    //print_r($proptype);
            
                    $numberOfResults = 0;
            
                    $sql = "SELECT DISTINCT webdevproject_properties.PropertyID,webdevproject_propertytypes.Name AS 'PropertyType',Headline,thumbnail_url,Price,Accomodates,Rating,NumberOfBeds,NumberOfBedrooms,NumberOfBathRooms,
                    webdevproject_cancellationpolicies.Name AS 'CancellationPolicy',webdevproject_cities.Name As 'City'
                    FROM webdevproject_properties
                    INNER JOIN webdevproject_propertytypes 
                    ON webdevproject_propertytypes.PropertyTypeID = webdevproject_properties.PropertyTypeID
                    INNER JOIN webdevproject_cancellationpolicies
                    ON webdevproject_properties.CancellationPolicyID = webdevproject_cancellationpolicies.PolicyID
                    INNER JOIN webdevproject_cities
                    ON webdevproject_cities.CityID = webdevproject_properties.CityID
                    WHERE webdevproject_properties.CityID = $city AND Accomodates >= $guests AND (";

            
                    //loop thru proptype array and add to sql statement
                    $arraySize = count($proptype);
                    for($i=0;$i<$arraySize-1;$i++){
                        $sql .= "webdevproject_properties.PropertyTypeID =$proptype[$i] OR ";
                    }
                    $arraySize -= 1;
                    $sql .= "webdevproject_properties.PropertyTypeID =$proptype[$arraySize]) ";
                    $sql .= "ORDER BY webdevproject_properties.$sortby $by ";
                

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
                    //echo json_encode($finalres);
                
                
                    //define how many results to display per page
                    $results_per_page = 20;
                
                    //determine the sql LIMIT starting number for the results on the current page
                    $currentPageFirstResult = ($pageNumber-1)*$results_per_page;
            
                    $sql .= "LIMIT $currentPageFirstResult,$results_per_page";
            
                    $result = $conn->query($sql);
            
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
            

                else  if(isset($_GET['location'],$_GET['accomodates'],$_GET['page'],$_GET['cancelpolicy'])){
                        
                    $city = $_GET['location'];
                    $guests = $_GET['accomodates'];
                    $pageNumber = $_GET['page'];
                    $cancelpolicy = $_GET['cancelpolicy']; //array
                    $sortby = $_GET['sortby'];

            
                    if($sortby=="Price"){
                        $by = " ASC";
                    }else{
                        $by = " DESC";
                    }
                

                    $numberOfResults = 0;

                    $sql = "SELECT webdevproject_properties.PropertyID,webdevproject_propertytypes.Name AS 'PropertyType',Headline,thumbnail_url,Price,Accomodates,Rating,NumberOfBeds,NumberOfBedrooms,NumberOfBathRooms,
                    webdevproject_cancellationpolicies.Name AS 'CancellationPolicy',webdevproject_cities.Name As 'City'
                    FROM webdevproject_properties
                    INNER JOIN webdevproject_propertytypes 
                    ON webdevproject_propertytypes.PropertyTypeID = webdevproject_properties.PropertyTypeID
                    INNER JOIN webdevproject_cancellationpolicies
                    ON webdevproject_properties.CancellationPolicyID = webdevproject_cancellationpolicies.PolicyID
                    INNER JOIN webdevproject_cities
                    ON webdevproject_cities.CityID = webdevproject_properties.CityID
                    WHERE webdevproject_properties.CityID = $city AND Accomodates >= $guests AND (";


                
                    //loop thru cancel policy array and add to sql statement
                    $arraySize = count($cancelpolicy);
                    for($i=0;$i<$arraySize-1;$i++){
                        $sql .= "webdevproject_cancellationpolicies.PolicyID =$cancelpolicy[$i] OR ";
                    }
                    $arraySize -= 1;
                    $sql .= "webdevproject_cancellationpolicies.PolicyID =$cancelpolicy[$arraySize]) ";
                    $sql .= "ORDER BY webdevproject_properties.$sortby $by ";
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
                    //echo json_encode($finalres);
                
                
                    //define how many results to display per page
                    $results_per_page = 20;
                
                    //determine the sql LIMIT starting number for the results on the current page
                    $currentPageFirstResult = ($pageNumber-1)*$results_per_page;

                    $sql .= "LIMIT $currentPageFirstResult,$results_per_page";

                    $result = $conn->query($sql);

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

                    else if(isset($_GET['location'],$_GET['accomodates'],$_GET['page'],$_GET['amenities'])){

                        $city = $_GET['location'];
                        $guests = $_GET['accomodates'];
                        $pageNumber = $_GET['page'];
                        $amenities = $_GET['amenities']; //array
                        $sortby = $_GET['sortby'];

            
                        if($sortby=="Price"){
                            $by = " ASC";
                        }else{
                            $by = " DESC";
                        }
        
                
                        $numberOfResults = 0;
                
                        $sql = "SELECT webdevproject_properties.PropertyID,webdevproject_propertytypes.Name AS 'PropertyType',Headline,thumbnail_url,Price,Accomodates,Rating,NumberOfBeds,NumberOfBedrooms,NumberOfBathRooms,
                        webdevproject_cancellationpolicies.Name AS 'CancellationPolicy',webdevproject_cities.Name As 'City'
                        FROM webdevproject_properties
                        INNER JOIN webdevproject_propertytypes 
                        ON webdevproject_propertytypes.PropertyTypeID = webdevproject_properties.PropertyTypeID
                        INNER JOIN webdevproject_cancellationpolicies
                        ON webdevproject_properties.CancellationPolicyID = webdevproject_cancellationpolicies.PolicyID
                        INNER JOIN webdevproject_cities
                        ON webdevproject_cities.CityID = webdevproject_properties.CityID
                        INNER JOIN webdevproject_propertyamenities1
                        ON webdevproject_properties.PropertyID = webdevproject_propertyamenities1.PropertyID
                        INNER JOIN webdevproject_amenities
                        ON webdevproject_propertyamenities1.AmenityID = webdevproject_amenities.AmenityID
                        WHERE webdevproject_properties.CityID = $city AND Accomodates >= $guests AND ( ";
                
                
                
                        //loop thru amenity policy array and add to sql statement
                        $arraySize = count($amenities);
                        for($i=0;$i<$arraySize-1;$i++){
                            $sql .= "webdevproject_amenities.AmenityID =$amenities[$i] OR ";
                        }
                        $arraySize -= 1;
                        $sql .= "webdevproject_amenities.AmenityID =$amenities[$arraySize]) ";
                        $sql .= "ORDER BY webdevproject_properties.$sortby $by ";
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
                        //echo json_encode($finalres);
                    
                    
                        //define how many results to display per page
                        $results_per_page = 20;
                    
                        //determine the sql LIMIT starting number for the results on the current page
                        $currentPageFirstResult = ($pageNumber-1)*$results_per_page;
                
                        $sql .= "LIMIT $currentPageFirstResult,$results_per_page";
                
                        $result = $conn->query($sql);
                
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

                        else if(isset($_GET['proptype'])){

                            $city = $_GET['location'];
                    $guests = $_GET['accomodates'];
                    $pageNumber = $_GET['page'];
                    $cancelpolicy = $_GET['cancelpolicy']; //array
                    $sortby = $_GET['sortby'];

            
                    if($sortby=="Price"){
                        $by = " ASC";
                    }else{
                        $by = " DESC";
                    }
                

                    $numberOfResults = 0;

                    $sql = "SELECT webdevproject_properties.PropertyID,webdevproject_propertytypes.Name AS 'PropertyType',Headline,thumbnail_url,Price,Accomodates,Rating,NumberOfBeds,NumberOfBedrooms,NumberOfBathRooms,
                    webdevproject_cancellationpolicies.Name AS 'CancellationPolicy',webdevproject_cities.Name As 'City'
                    FROM webdevproject_properties
                    INNER JOIN webdevproject_propertytypes 
                    ON webdevproject_propertytypes.PropertyTypeID = webdevproject_properties.PropertyTypeID
                    INNER JOIN webdevproject_cancellationpolicies
                    ON webdevproject_properties.CancellationPolicyID = webdevproject_cancellationpolicies.PolicyID
                    INNER JOIN webdevproject_cities
                    ON webdevproject_cities.CityID = webdevproject_properties.CityID
                    WHERE webdevproject_properties.CityID = $city AND Accomodates >= $guests AND (";


                
                    //loop thru cancel policy array and add to sql statement
                    $arraySize = count($cancelpolicy);
                    for($i=0;$i<$arraySize-1;$i++){
                        $sql .= "webdevproject_cancellationpolicies.PolicyID =$cancelpolicy[$i] OR ";
                    }
                    $arraySize -= 1;
                    $sql .= "webdevproject_cancellationpolicies.PolicyID =$cancelpolicy[$arraySize]) ";
                    $sql .= "ORDER BY webdevproject_properties.$sortby $by ";
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
                    //echo json_encode($finalres);
                
                
                    //define how many results to display per page
                    $results_per_page = 20;
                
                    //determine the sql LIMIT starting number for the results on the current page
                    $currentPageFirstResult = ($pageNumber-1)*$results_per_page;

                    $sql .= "LIMIT $currentPageFirstResult,$results_per_page";

                    $result = $conn->query($sql);

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

                    else if(isset($_GET['location'],$_GET['accomodates'],$_GET['page'],$_GET['amenities'])){

                        $city = $_GET['location'];
                        $guests = $_GET['accomodates'];
                        $pageNumber = $_GET['page'];
                        $amenities = $_GET['amenities']; //array
                        $sortby = $_GET['sortby'];

            
                        if($sortby=="Price"){
                            $by = " ASC";
                        }else{
                            $by = " DESC";
                        }
        
                
                        $numberOfResults = 0;
                
                        $sql = "SELECT webdevproject_properties.PropertyID,webdevproject_propertytypes.Name AS 'PropertyType',Headline,thumbnail_url,Price,Accomodates,Rating,NumberOfBeds,NumberOfBedrooms,NumberOfBathRooms,
                        webdevproject_cancellationpolicies.Name AS 'CancellationPolicy',webdevproject_cities.Name As 'City'
                        FROM webdevproject_properties
                        INNER JOIN webdevproject_propertytypes 
                        ON webdevproject_propertytypes.PropertyTypeID = webdevproject_properties.PropertyTypeID
                        INNER JOIN webdevproject_cancellationpolicies
                        ON webdevproject_properties.CancellationPolicyID = webdevproject_cancellationpolicies.PolicyID
                        INNER JOIN webdevproject_cities
                        ON webdevproject_cities.CityID = webdevproject_properties.CityID
                        INNER JOIN webdevproject_propertyamenities1
                        ON webdevproject_properties.PropertyID = webdevproject_propertyamenities1.PropertyID
                        INNER JOIN webdevproject_amenities
                        ON webdevproject_propertyamenities1.AmenityID = webdevproject_amenities.AmenityID
                        WHERE webdevproject_properties.CityID = $city AND Accomodates >= $guests AND ( ";
                
                
                
                        //loop thru amenity policy array and add to sql statement
                        $arraySize = count($amenities);
                        for($i=0;$i<$arraySize-1;$i++){
                            $sql .= "webdevproject_amenities.AmenityID =$amenities[$i] OR ";
                        }
                        $arraySize -= 1;
                        $sql .= "webdevproject_amenities.AmenityID =$amenities[$arraySize]) ";
                        $sql .= "ORDER BY webdevproject_properties.$sortby $by ";
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
                        //echo json_encode($finalres);
                    
                    
                        //define how many results to display per page
                        $results_per_page = 20;
                    
                        //determine the sql LIMIT starting number for the results on the current page
                        $currentPageFirstResult = ($pageNumber-1)*$results_per_page;
                
                        $sql .= "LIMIT $currentPageFirstResult,$results_per_page";
                
                        $result = $conn->query($sql);
                
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

                        else if(isset($_GET['proptype'])){
                    
                            $pageNumber = $_GET['page'];
                            $proptype = $_GET['proptype']; //array
            
                            $sortby = $_GET['sortby'];
            
                    
                            if($sortby=="Price"){
                                $by = " ASC";
                            }else{
                                $by = " DESC";
                            }
            
                            //print_r($proptype);
                    
                            $numberOfResults = 0;
                    
                            $sql = "SELECT DISTINCT webdevproject_properties.PropertyID,webdevproject_propertytypes.Name AS 'PropertyType',Headline,thumbnail_url,Price,Accomodates,Rating,NumberOfBeds,NumberOfBedrooms,NumberOfBathRooms,
                            webdevproject_cancellationpolicies.Name AS 'CancellationPolicy',webdevproject_cities.Name As 'City'
                            FROM webdevproject_properties
                            INNER JOIN webdevproject_propertytypes 
                            ON webdevproject_propertytypes.PropertyTypeID = webdevproject_properties.PropertyTypeID
                            INNER JOIN webdevproject_cancellationpolicies
                            ON webdevproject_properties.CancellationPolicyID = webdevproject_cancellationpolicies.PolicyID
                            INNER JOIN webdevproject_cities
                            ON webdevproject_cities.CityID = webdevproject_properties.CityID
                            WHERE (";
            
                    
                            //loop thru proptype array and add to sql statement
                            $arraySize = count($proptype);
                            for($i=0;$i<$arraySize-1;$i++){
                                $sql .= "webdevproject_properties.PropertyTypeID =$proptype[$i] OR ";
                            }
                            $arraySize -= 1;
                            $sql .= "webdevproject_properties.PropertyTypeID =$proptype[$arraySize]) ";
                            $sql .= "ORDER BY webdevproject_properties.$sortby $by ";
                        
            
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
                            //echo json_encode($finalres);
                        
                        
                            //define how many results to display per page
                            $results_per_page = 20;
                        
                            //determine the sql LIMIT starting number for the results on the current page
                            $currentPageFirstResult = ($pageNumber-1)*$results_per_page;
                    
                            $sql .= "LIMIT $currentPageFirstResult,$results_per_page";
                    
                            $result = $conn->query($sql);
                    
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