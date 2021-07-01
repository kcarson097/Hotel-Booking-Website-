<?php

  //this is needed so that the user can go back to the prev page from other pages and it will remember the previous search by the user
  header('Cache-Control: no cache'); 
  session_cache_limiter('private_no_expire'); 

    session_start();
    
    if($_SESSION['login'] == 0 || $_SESSION['admin'] == 1){//user is not authorised for this page redirect them !
      header("Location: index.php");
    }

     //this will only open when the user has opted to favourite a property and so the property id will have been posted
     $PropertyID = $_POST['fav'];
     $prevurl = $_POST['prevurl'];

      //check if the user has already liked this property
      $checkExistsEndpoint = "http://kcarson09.lampt.eeecs.qub.ac.uk/mybnbapi/accounts/favourites.php?propID=$PropertyID&userID={$_SESSION['userID']}";
      $resultExists = file_get_contents($checkExistsEndpoint);
      $dataExists = json_decode($resultExists,true);
      if($dataExists['Number of results'] == 0){//doesnt exist so can enter
        $exists = false;
        //insert favourite into favourites table and link it to the user
        $insertEndpoint = "http://kcarson09.lampt.eeecs.qub.ac.uk/mybnbapi/accounts/addfavourite.php?propID=$PropertyID&userID={$_SESSION['userID']}";
        $result = file_get_contents($insertEndpoint);
        //update the number of likes of the property in the main properties table
        $updatelikes = "http://kcarson09.lampt.eeecs.qub.ac.uk/mybnbapi/property/insertproplike.php?propID=$PropertyID";
        $result2 = file_get_contents($updatelikes);

        
        //connect to api and get all the property details
        $endpoint = "http://kcarson09.lampt.eeecs.qub.ac.uk/mybnbapi/property/property.php?propertyID=$PropertyID";
        $result = file_get_contents($endpoint);
        $data = json_decode($result,true);

        $headline = $data[0]["Headline"];
        $thumbnailurl = $data[0]["PropPageImage"];
        $price = $data[0]["Price"];
        $accomodates = $data[0]["Accomodates"];
        $personIcons = getOccupancyIcons($accomodates);
        $rating = $data[0]["Rating"];
        $stars = getStarRating($rating); //convert the rating into star icons
        $beds = $data[0]["NumberOfBeds"];
        $bathrooms = $data[0]["NumberOfBathRooms"];
        $cityID = $data[0]["City"];
        $cancellationPolicy = $data[0]["CancellationPolicy"];
        $propType = $data[0]["PropertyType"];

    }else{
      $exists = true;
    }

    //reads in rating and creates appropriate number of stars
      function getStarRating($rating){
        $statement = "";
        for($int= 0;$int<$rating;$int++){
           $statement .= "<span class='fa fa-star checked' style='font-size:12px'></span>";
        }
        return $statement;
    }

    function getOccupancyIcons($occupancy){
      $statement = "$occupancy";
      $statement .= "x <span><i class='fa fa-male'></i></span>";
      return $statement;

    }

    
    
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Latest compiled and minified CSS for Bootstrap Select -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <!-- Latest compiled and minified JavaScript for bootstrap select -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.3.0/mdb.min.js"></script>
    <link rel="stylesheet" href="css/ui.css">
    <title>MyBnB</title>
    
</head>


<body>

<section class = "header">
  

  <nav class="navbar navbar-expand-lg navbar-dark bg-primary py-2">
    <a class="navbar-brand" href="index.php">MyBnb</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse flex-row-reverse buttons"  buttons id="navbarNav">
      <ul class="navbar-nav">
      <?php
      if($_SESSION['login'] == 0){
        echo "  <li class='nav-item active'>
                   <a class='nav-link' href='createaccount.php'>Create New Account <span class='sr-only'>(current)</span></a>
                 </li>
                <li class='nav-item active'>
                  <a class='nav-link' href='login.php'>Login</a>
                </li>";
                }else if($_SESSION['admin'] == 0){//just a regular user logged in
                  echo "    <li class='nav-item active'>
                              <a class='nav-link' href='myfavourites.php'>My Favourites</a>
                            </li>
                            <li class='nav-item active'>
                              <a class='nav-link' href='mybookings.php'>My Bookings</a>
                            </li><li class='nav-item active'>
                            <a class='nav-link' href='loggedout.php'>Logout</a>
                            </li>
                        ";
                }else{//admin is logged on
                  echo "
                    <li class='nav-item active'>
                      <a class='nav-link' href='createlisting.php'>Create Listing</a>
                    </li>
                    <li class='nav-item active'>
                      <a class='nav-link' href='loggedout.php'>Logout</a>
                    </li>";
                }
        ?>
      </ul>
    </div>
  </nav>

</section>

<section id = "main">


   <div class = "login2">

  <div class="container">
	<div class="d-flex justify-content-center favcard">
  <?php

    if($exists == false){
        echo "
                <div class='card'>
                <div class='card-header logouttext favheader'>
                Added To Favourites : 
                </div>
                <div class = 'card-body favcards'>";
                  
                  echo "<p class = 'logouttext'>You have Favourited $headline !</p>
                  
                <div class='card' style='width: 18rem;''>";
               
                echo"
                    <img class='card-img-top' src='$thumbnailurl' alt='Card image cap'>
              <div class='card-body favtext'>
              <h5 class='card-title'>$headline        
              $stars
              </h5>
                    
                <p class='card-text'>
                                $cityID
                                $personIcons
                                $propType 
                                <span><i class='fa fa-bed'></i></span>
                                $beds Bed(s)
                                <span><i class='fa fa-bath'></i></span>
                                $bathrooms Bathroom(s)
                                </p> 
                                <p class = 'card-text'> <span style = 'color:green'>$cancellationPolicy Cancellation</span></p>
                                <p class='card-text'>Starting from <strong>$$price</strong></p></p>
              
              </div>
              <div class = 'card-footer favheader'>";
                echo " <a class = 'logouttext' href = '$prevurl'>&nbspClick here to go back</a></p>
                      <a class = 'logouttext' href = 'myfavourites.php'>&nbspClick here to go view all your favourites</a></p>";

    }else{
      echo"
      <div class='card'>
        <div class = 'card-body'>
        <p class = 'createaccounttextfail'>You have already added this property as a favourite</p>
        <a class = 'logouttext' href = '$prevurl'>&nbspClick here to go back</a></p>
        <a class = 'logouttext' href = 'myfavourites.php'>&nbspClick here to go view all your favourites</a></p>
         </div>
    </div>";
    }
                ?>
                
  </div>
</div>
         </div>
    </div>
    </div>
  
            
       

     


</section>

  </section>


  <section id = "footerlogin">

    <div class="row">
      <div class="col-sm-3 text-center">
        <div>
          <p1><strong>Account</strong></p1>
        </div>
        <?php
        if($_SESSION['login'] == 0){
          echo " <div>
                    <p1><a class = 'footertext' href = 'createaccount.php'>Create Account</a></p1>
                  </div>
                  <div>
                    <p1><a class = 'footertext' href = 'login.php'>Sign In</a></p1>
                  </div>
                </div>";
        }else if($_SESSION['admin'] == 1){
          echo "<div>
                  <p1><a class = 'footertext' href = 'createlisting.php'>Create Listing</a></p1>
                </div>
                <div>
                    <p1><a class = 'footertext' href = 'loggedout.php'>Logout</a></p1>
                  </div>
              </div>";
        }else{
          echo " <div>
                    <p1><a class = 'footertext' href = 'myfavourites.php'>View My Favourites</a></p1>
                  </div>
                  <div>
                    <p1><a class = 'footertext' href = 'mybookings.php.php'>View My Bookings</a></p1>
                  </div>
                  <div>
                    <p1><a class = 'footertext' href = 'loggedout.php'>Logout</a></p1>
                  </div>
                </div>";
        }
        ?>
       
      
      <div class="col-sm-3 text-center"></div>
        
      <div class="col-sm-3 text-center"></div>
      
      <div class="col-sm-3 text-center">
        <p1><a class = 'footertext' href = 'generateapi.php'>Developer APIs</a></p1>
        <p>&copy;MyBnb</p>
      </div>
    
    
  </section>

  </body>
  </html>