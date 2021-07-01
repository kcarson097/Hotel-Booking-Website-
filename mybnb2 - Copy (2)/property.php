<?php

// //this is needed so that the user can go back to the prev page from other pages and it will remember the previous search by the user
// header('Cache-Control: no cache'); 
// session_cache_limiter('private_no_expire'); 

session_start();

$propID = $_GET['propertyID'];


if(!isset($_SESSION['login'])){
  $_SESSION['login'] = 0;
}
//reads in rating and creates appropriate number of stars
function getStarRating($rating){
    $statement = "";
    for($int= 0;$int<$rating;$int++){
         $statement .= "<span class='fa fa-star checked' style='font-size:12px'></span>";
    }
    return $statement;
    }

if(isset($_GET['propertyID'])){
    $propID = $_GET['propertyID'];
    $endpoint1 = "http://kcarson09.lampt.eeecs.qub.ac.uk/mybnbapi/property/property.php?propertyID=$propID"; //returns all prop details needed bar amenities
    $result1 = file_get_contents($endpoint1);
    $data1 = json_decode($result1,true);

    $endpoint2 = "http://kcarson09.lampt.eeecs.qub.ac.uk/mybnbapi/property/propertyammenities.php?propertyID=$propID"; //returns all prop amenities (stored in its own separate table so need the two api calls)
    $result2 = file_get_contents($endpoint2);
    $data2 = json_decode($result2,true);

    foreach($data1 as $row){//prop details
        $proptype = $row["PropertyType"];
        $roomtype = $row["RoomType"];
        $headline = $row["Headline"];
        $desc = $row["Description"];
        $image = $row["PropPageImage"];
        $price = $row["Price"];
        $accomodates = $row["Accomodates"];
        $stars = getStarRating($row["Rating"]);
        $numberofreviews = $row["NumberOfReviews"];
        $beds = $row["NumberOfBeds"];
        $bedrooms = $row["NumberOfBedrooms"];
        $bathrooms = $row["NumberOfBathRooms"];
        $cancellation = $row["CancellationPolicy"];
        $city = $row["City"];
        $hostname = $row["HostName"];
        $hostrating = $row["HostRating"];
        $responserate = $row["ResponseRate"];
        $zipcode = $row["ZipCode"];
   

    }
    $propAmenities = array();
    foreach($data2 as $row1){//prop amenities
        array_push($propAmenities,$row1["Amenity"]);
    }
    $numberOfAmens = count($propAmenities);
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<!-- Latest compiled and minified CSS for Bootstrap Select -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
<!-- Latest compiled and minified JavaScript for bootstrap select -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.3.0/mdb.min.js"></script>
<link rel="stylesheet" href="css/ui.css">
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<title>MyBnb</title>

</head>

<body>

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
    
    <div class="row">
        <div class="col-lg-12">
            <div>
              <?php
                echo "<h1 class = 'titles'>$headline</h1>";
                     
                  
                echo "<p1 class = 'textprop'><strong>Price per night : </strong>$$price</p1>";
  
                echo "<p1 class = 'titles'>
                    <strong>Rating : </strong>
                    $stars
                    ($numberofreviews)
                </p1>";
                echo "<p1  class = 'textprop'><strong>Location : </strong>$city<strong> Zip : </strong>$zipcode</p1>";
            ?>

                <?php
                if(($_SESSION['login'] == 1) && ($_SESSION['admin']!=1)){
                    echo "<form method = 'POST' action = 'makebooking.php' style = 'display:inline'>
                          <input type = 'hidden' name = 'propID' value = $propID>
                          <button type='submit' name = 'prevurl' value = {$_SERVER['REQUEST_URI']} class='btn btn-primary btn-md'>Make Booking</button>
                          </form>";
                }else if($_SESSION['admin'] == 1){
                  echo "<form method = 'POST' action = 'editproperty.php' style = 'display:inline'>
                            <input type = 'hidden' name = 'propID' value = $propID>
                            <button type='submit' name = 'prevurl' value = {$_SERVER['REQUEST_URI']} class='btn btn-primary btn-md'>Edit Listing</button>
                        </form>
                        <form method = 'POST' action = 'deleteproperty.php' style = 'display:inline'>
                            <input type = 'hidden' name = 'propID' value = $propID>
                            <button type='submit' name = 'prevurl' value = {$_SERVER['REQUEST_URI']} class='btn btn-primary btn-md'>Delete Listing</button>
                        </form>";
                }else{
                  echo "
                    <button type='button' class='btn btn-primary btn-md' data-toggle = 'modal' data-target='#likemodal'>Make Booking</button>
                 ";
               }
                
                ?>
               
                </div>
            </div>
        </div>

        


<div class="row head">
    <div class="col-lg-6">
      <?php
        echo "
        <div class = 'container3'>
        <img class = 'propertyimg' src = $image >";
        if($_SESSION['login'] == 0 || $_SESSION['admin'] == 1){
          echo "<button type='button' class = 'btn4' data-toggle = 'modal' data-target='#likemodal'><i class='fa fa-heart-o' aria-hidden='true'></i></button>";                  
        }
        else{
          echo "<form method = POST action = 'addFavourite.php'>
                <input type = 'hidden' name = 'prevurl' value = {$_SERVER['REQUEST_URI']}>
                <button type='submit' value = $propID name = 'fav' class='btn4'><i class='fa fa-heart-o' aria-hidden='true'></i></button>
                </form>";
        }
        echo "</div>";
      ?>
      </div>
    

    
<div class="col-lg-6 ">
<div class="row propdetails">
  <div class="col">
      <div class="card">
          <div class="card-body">
              <p1><strong>Property Summary</strong></p1>
          </div>
          <div class="row">
              <div class="col">
                <?php
                 echo" <ul>
                    <li>Accomodates : $accomodates</li>
                    <li>Property Type : $proptype</li>
                    <li>Room Type : $roomtype</li>
                    <li>Bedrooms : $bedrooms</li>
                  </ul>
              </div>
              <div class='col'>
                  <ul>
                      <li>Beds : $beds</li>
                      <li>Bathrooms : $bathrooms</li>
                      <li>$cancellation Cancellation</li>
                  </ul>";
              ?>
              </div>
          </div>
        </div>
      </div>
</div>

<div class="row propdetails">
<div class="col">
      <div class="card">
        <div class="card-body">
          <p1><strong>Ammenities</strong></p1>
          </div>
      <div class="row">
        <div class="col">
          <ul>
        <?php
            for($i = 0;$i<ceil($numberOfAmens/2);$i++){
              echo "<li>$propAmenities[$i]</li>";               
            }  
        ?>
        </ul>
        </div>
        <div class="col">
          <ul>
          <?php
           for($i = ceil($numberOfAmens/2);$i<$numberOfAmens;$i++){
            echo "<li>$propAmenities[$i]</li>";  
           }
          ?>
         </ul>

           </div>
        </div>
       </div>
    </div>
    </div>
   
<?php
  if($numberOfAmens<=18){//i.e can display host details in same col as ammenities 
    echo "<div class='row propdetails'>
    <div class='col-md-12'>
      <div class='card'>
          <div class='card-body'>
            <p1><strong>Your Host</strong></p1>
          </div>
              <ul>
                  <li>Host Name : $hostname</li>
                  <li>Host Rating : $hostrating/5</li>
                  <li>Host Response Rate : $responserate %</li>
                  
                </ul>
            </div>
        </div>
             
     </div>
  </div>";
  }else{
    echo"</div></div>
    <div class='row propdetails'>
    <div class='col-md-12'>
      <div class='card'>
          <div class='card-body'>
            <p1><strong>Your Host</strong></p1>
          </div>
              <ul>
                  <li>Host Name : $hostname</li>
                  <li>Host Rating : $hostrating/5</li>
                  <li>Host Response Rate : $responserate %</li>
                  
                </ul>
            </div>
        </div>
             
     </div>
  </div>";

  }
  ?>

  <div class="row propdetails">
  <div class="col-md-12">
      <div class="card">
          <div class="card-body">
              <p1><strong>Property Description</strong></p1>
              <?php
                  echo "<br>";
                  echo "<p>$desc</p>";
              ?>
          </div>
          
        </div>
      </div>
          </div>
          </div>

  <!--Like Modal-->
  <div class="modal fade" id="likemodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Notice</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id = "likes">
        <?php
        echo "<p>You need to be logged in as a user to use this feauture !</p>
                  <p><a href = 'login.php'>Click here to login</a></p>
                  <p><a href = 'createaccount.php'>Click here to create an account</a></p>
          ";
        ?>
      </div>
    </div>
  </div>
</div>

         
<section id = "footer">

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