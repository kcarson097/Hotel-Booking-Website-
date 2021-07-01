<?php

session_start();

if($_SESSION['login'] == 0 || $_SESSION['admin'] == 0){//user is not authorised for this page redirect them !
  header("Location: index.php");
}

//get all variables that were posted 
$propID = $_POST['propID'];
$propType = $_POST['propType'];
$roomtype = $_POST['roomtype'];
$city = $_POST['city'];
$host = $_POST['host'];
$headline = urlencode($_POST['headline']); //encode all string vars for the url
$description = urlencode($_POST['description']);
$accomodates = $_POST['accomodates'];
$reviews = $_POST['reviews'];
$cancelpolicy = $_POST['cancelpolicy'];
$rating = $_POST['rating'];
$zipcode = urlencode($_POST['zipcode']);
$price = $_POST['price'];
$beds = $_POST['beds'];
$bedrooms = $_POST['bedrooms'];
$bathrooms = $_POST['bathrooms'];
$thumbnail = urlencode($_POST['thumbnail']);
$mainimage = urlencode($_POST['proppageimage']);

if(isset($_POST['amenities'])){
    $ammens = $_POST['amenities'];//array
  }

//call update api endpoint - this updates the properties table for the specified id
$endpoint = "http://kcarson09.lampt.eeecs.qub.ac.uk/mybnbapi/admin/editlisting.php?propID={$propID}&propType={$propType}&roomtype={$roomtype}&city={$city}&host={$host}&headline='{$headline}'&description='{$description}'&accomodates={$accomodates}&reviews={$reviews}&cancelpolicy={$cancelpolicy}&rating={$rating}&zipcode='{$zipcode}'&price={$price}&beds={$beds}&bedrooms={$bedrooms}&bathrooms={$bathrooms}&thumbnail='{$thumbnail}'&proppageimage='{$mainimage}'";
//echo $endpoint;
$result = file_get_contents($endpoint);
$data = json_decode($result,true);

if(isset($ammens)){
    //delete all amenities in prop amenities table for this ID - i.e deleting old amenities for that property
    $endpoint = "http://kcarson09.lampt.eeecs.qub.ac.uk/mybnbapi/admin/deleteamens.php?propID={$propID}";
    $result = file_get_contents($endpoint);
    $data = json_decode($result,true);
    //now insert all the new amenity values for the property 
    for($i=0;$i<count($ammens);$i++){
      $amenID = $ammens[$i];
      $endpoint1 = "http://kcarson09.lampt.eeecs.qub.ac.uk/mybnbapi/admin/addamens.php?propID={$propID}&amenity={$amenID}";
      //echo $endpoint1;
      $result1 = file_get_contents($endpoint1);
  }

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
    <title>Listing Updated</title>
    
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

   <div class = "login">

  <div class="container">
	<div class="d-flex justify-content-center">
		<div class="card">
        <div class = "card-body">
        <?php
        echo "
        <p class = 'logouttext'>You have successfully updated the listing<a href = 'index.php' >&nbspClick here to go home</a></p>
        <a href = 'property.php?propertyID=$propID' >&nbspClick here to view the listing</a>"
        ?>
         </div>
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

