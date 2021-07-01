<?php
//ini_set('session.cookie_secure',1);
session_start();

// // unset cookies
// if (isset($_SERVER['HTTP_COOKIE'])) {
//   $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
//   foreach($cookies as $cookie) {
//       $parts = explode('=', $cookie);
//       $name = trim($parts[0]);
//       setcookie($name, '', time()-1000);
//       setcookie($name, '', time()-1000, '/');
//   }
// }
// if (isset($_COOKIE["PHPSESSID"])) {
//   session_destroy();
// } else {
  
// }

if(!isset($_SESSION['login'])){
  $_SESSION['login'] = 0;
  $_SESSION['userID'] = "";
  $_SESSION['username'] = "";
  $_SESSION['admin'] = 0;
}

// if(isset($_POST['hidlike'])){
//   echo $_POST['hidlike'];
// }

//print_r($_SESSION);


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
    <title>MyBnb</title>
    
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

    <section id = "search">

    <div class="jumbotron jumbotron-fluid">
      <div class="container">

        <h1 class="display-4 text1"><span>Plan Your Next Adventure Today</span></h1>
      </div>


    <div class="d-flex justify-content-center searches  style='min-height: calc(20vh)'">
    <form method = "POST" action = 'search.php' name = "search">
    <div class="form-group needs-validation"novalidate>
        
        <span>
            <select class="selectpicker form" name = 'citybar' data-width = '260px' data-live-search = "true" id = 'citybar' searchable="Search here.."required>
                <option class = "special" value="" disabled selected>Where to ?</option>
                <?php
                    //call api to get all the cities in the db
                    $endpoint = "http://kcarson09.lampt.eeecs.qub.ac.uk/mybnbapi/names/cityNames.php";
                    $result = file_get_contents($endpoint);
                    $data = json_decode($result,true);
                    foreach($data as $row){
                      $name = $row['Name'];
                      $id = $row['CityID'];
                      echo " <option value = $id class = 'special' >$name</option>";
                    }
                ?>
              </select>
        </span>
        <span>
            <select class="selectpicker special" name = 'guestbar' data-width = '260px'  searchable="Search here.."required>
                <option value="" disabled selected>Number of Guests</option>
                <option value="1" name = "guestbar">1</option>
                <option value="2" name = "guestbar">2</option>
                <option value="3" name = "guestbar">3</option>
                <option value="4" name = "guestbar">4</option>
                <option value="5" name = "guestbar">5+</option>
              </select>
        </span>
        <span>
                <button type="submit" id = "searchbutton" value = "submit" class="btn btn-info" > Search</button>
      </span>
      </div>	
      </form>
    </div>	
    </div>    
  </section>


  <section id = "cards">

    <div class = "titlesindex">
        <h1><span>Trending Listings</span></h1>
    </div>

    <?php

    //connect to api and get top 5 trending properties  (most liked properties)
    $endpoint = 'http://kcarson09.lampt.eeecs.qub.ac.uk/mybnbapi/property/trendingproperties.php';
    $result = file_get_contents($endpoint);
    $data = json_decode($result,true);

    //loop through properties and create proprty card to display to page
    // echo "<div class = 'row cards'";
    for($index=0;$index<5;$index++){
        //get all the property details needed for the search result
      
        $PropertyID = $data[$index]["PropertyID"];
        $headline = $data[$index]["Headline"];
        $thumbnailurl = $data[$index]["thumbnail_url"];
        $price = $data[$index]["Price"];
        $accomodates = $data[$index]["Accomodates"];
        $personIcons = getOccupancyIcons($accomodates);
        $rating = $data[$index]["Rating"];
        $stars = getStarRating($rating); //convert the rating into star icons
        $beds = $data[$index]["NumberOfBeds"];
        $bathrooms = $data[$index]["NumberOfBathRooms"];
        $cityID = $data[$index]["City"];

        //cancellation policy
        $cancellationPolicy = $data[$index]["CancellationPolicy"];
        //PropertyType
        $propType = $data[$index]["PropertyType"]; 
        if($index == 0){
          echo "<div class = 'row cards'
          <div class='col-sm'>";
        }
        
        echo" 
        <div class='col-sm'>
        <div class='card h-100'>
        <div class='container5'>
        <img class='card-img-top' src = '$thumbnailurl'>";
          if(($_SESSION['login'] == 0) || ($_SESSION['admin'] == 1)){
            echo "<button type='button' class = 'btn5' data-toggle = 'modal' data-target='#likemodal'><i class='fa fa-heart-o' aria-hidden='true'></i></button>";                  
          }
          else{
            echo "<form method = POST action = 'addFavourite.php'>
                  <input type = 'hidden' name = 'prevurl' value = {$_SERVER['REQUEST_URI']}>
                  <button type='submit' value = $PropertyID name = 'fav' class='btn5'><i class='fa fa-heart-o' aria-hidden='true'></i></button>
                  </form>";
          }
        echo"
          
        <a href = 'property.php?propertyID=$PropertyID' id = 'cardlink'> 
          <div class='card-body'>
            <h5 class='card-title'>$headline        
            $stars
            </h5>
            <p class = 'card-text'>
            $cityID
            $personIcons
            $propType
            <span><i class='fa fa-bed'></i></span>
            $beds Bed(s)
            <span><i class='fa fa-bath'></i></span>
            $bathrooms Bathroom(s)
            </p> 
            <p class = 'card-text'> <span style = 'color:green'>$cancellationPolicy Cancellation</span></p>
            <p class='card-text'>Starting from <strong>$$price</strong></p>";
            if($_SESSION['admin'] == 1){//admin is logged in give ability to edit
              echo "
                <form method = POST action = 'editproperty.php' style = 'display:inline'>
                    <input type = 'hidden' name = 'prevurl' value = {$_SERVER['REQUEST_URI']}>
                    <button type = 'submit' class='btn btn-primary btn-md' name = 'propID' value = $PropertyID>Edit Listing</button>
                </form>
                <form method = 'POST' action = 'deleteproperty.php' style = 'display:inline'>
                  <input type = 'hidden' name = 'propID' value = $PropertyID>
                  <button type='submit' name = 'prevurl' value = {$_SERVER['REQUEST_URI']} class='btn btn-primary btn-md'>Delete Listing</button>
                </form>
                ";
              }
            echo"
            </div>
          </div>
      </div>
      </a>
      </div>
      
    ";
    }
    echo "</div>";//close the row


    ?>



    <div class = "titlesindex">
      <h1><span>Trending in LA</span></h1>
    </div>

    <?php

        //connect to api and get top 5 trending properties in LA (most liked properties)
        $endpoint = 'http://kcarson09.lampt.eeecs.qub.ac.uk/mybnbapi/property/trendingproperties.php?cityID=2';
        $result = file_get_contents($endpoint);
        $data = json_decode($result,true);

        //loop through properties and create proprty card to display to page
       // echo "<div class = 'row cards'";
        for($index=0;$index<5;$index++){
            //get all the property details needed for the search result
          
            $PropertyID = $data[$index]["PropertyID"];
            $headline = $data[$index]["Headline"];
            $thumbnailurl = $data[$index]["thumbnail_url"];
            $price = $data[$index]["Price"];
            $accomodates = $data[$index]["Accomodates"];
            $personIcons = getOccupancyIcons($accomodates);
            $rating = $data[$index]["Rating"];
            $stars = getStarRating($rating); //convert the rating into star icons
            $beds = $data[$index]["NumberOfBeds"];
            $bathrooms = $data[$index]["NumberOfBathRooms"];
            $cityID = $data[$index]["City"];

            //cancellation policy
            $cancellationPolicy = $data[$index]["CancellationPolicy"];
            //PropertyType
            $propType = $data[$index]["PropertyType"]; 
            if($index == 0){
              echo "<div class = 'row cards'
              <div class='col-sm'>";
            }
            
            echo" 
            <div class='col-sm'>
            <div class='card h-100'>
            <div class='container5'>
            <img class='card-img-top' src = '$thumbnailurl'>";
              if($_SESSION['login'] == 0){
                echo "<button type='button' class = 'btn5' data-toggle = 'modal' data-target='#likemodal'><i class='fa fa-heart-o' aria-hidden='true'></i></button>";                  
              }
              else{
                echo "<form method = POST action = 'addFavourite.php'>
                      <input type = 'hidden' name = 'prevurl' value = {$_SERVER['REQUEST_URI']}>
                      <button type='submit' value = $PropertyID name = 'fav' class='btn5'><i class='fa fa-heart-o' aria-hidden='true'></i></button>
                      </form>";
              }
            echo"
              
            <a href = 'property.php?propertyID=$PropertyID' id = 'cardlink'> 
              <div class='card-body'>
                <h5 class='card-title'>$headline        
                $stars
                </h5>
                <p class = 'card-text'>
                $cityID
                $personIcons
                $propType
                <span><i class='fa fa-bed'></i></span>
                $beds Bed(s)
                <span><i class='fa fa-bath'></i></span>
                $bathrooms Bathroom(s)
                </p> 
                <p class = 'card-text'> <span style = 'color:green'>$cancellationPolicy Cancellation</span></p>
                <p class='card-text'>Starting from <strong>$$price</strong></p>";
                if($_SESSION['admin'] == 1){//admin is logged in give ability to edit
                  echo "
                    <form method = POST action = 'editproperty.php' style = 'display:inline'>
                      <input type = 'hidden' name = 'prevurl' value = {$_SERVER['REQUEST_URI']}>
                      <button type = 'submit' class='btn btn-primary btn-md' name = 'prop' value = $PropertyID>Edit Listing</button>
                    </form>
                    <form method = 'POST' action = 'deleteproperty.php' style = 'display:inline'>
                      <input type = 'hidden' name = 'propID' value = $PropertyID>
                      <button type='submit' name = 'prevurl' value = {$_SERVER['REQUEST_URI']} class='btn btn-primary btn-md'>Delete Listing</button>
                    </form>";
                  }
                echo"
                </div>
              </div>
           </div>
          </a>
          </div>
          
        ";
        }
        echo "</div>";//close the row
        

    ?>

  <div class = "titlesindex">
        <h1><span>Trending in NYC</span></h1>
  </div>

  <?php

  //connect to api and get top 5 trending properties in LA (most liked properties)
  $endpoint = 'http://kcarson09.lampt.eeecs.qub.ac.uk/mybnbapi/property/trendingproperties.php?cityID=3';
  $result = file_get_contents($endpoint);
  $data = json_decode($result,true);

  //loop through properties and create proprty card to display to page
  // echo "<div class = 'row cards'";
  for($index=0;$index<5;$index++){
      //get all the property details needed for the search result
    
      $PropertyID = $data[$index]["PropertyID"];
      $headline = $data[$index]["Headline"];
      $thumbnailurl = $data[$index]["thumbnail_url"];
      $price = $data[$index]["Price"];
      $accomodates = $data[$index]["Accomodates"];
      $personIcons = getOccupancyIcons($accomodates);
      $rating = $data[$index]["Rating"];
      $stars = getStarRating($rating); //convert the rating into star icons
      $beds = $data[$index]["NumberOfBeds"];
      $bathrooms = $data[$index]["NumberOfBathRooms"];
      $cityID = $data[$index]["City"];

      //cancellation policy
      $cancellationPolicy = $data[$index]["CancellationPolicy"];
      //PropertyType
      $propType = $data[$index]["PropertyType"]; 
      if($index == 0){
        echo "<div class = 'row cards'
        <div class='col-sm'>";
      }
      
      echo" 
      <div class='col-sm'>
      <div class='card h-100'>
      <div class='container5'>
      <img class='card-img-top' src = '$thumbnailurl'>";
        if($_SESSION['login'] == 0){
          echo "<button type='button' class = 'btn5' data-toggle = 'modal' data-target='#likemodal'><i class='fa fa-heart-o' aria-hidden='true'></i></button>";                  
        }
        else{
          echo "<form method = POST action = 'addFavourite.php'>
                <input type = 'hidden' name = 'prevurl' value = {$_SERVER['REQUEST_URI']}>
                <button type='submit' value = $PropertyID name = 'fav' class='btn5'><i class='fa fa-heart-o' aria-hidden='true'></i></button>
                </form>";
        }
      echo"

      <a href = 'property.php?propertyID=$PropertyID' id = 'cardlink'> 
        <div class='card-body'>
          <h5 class='card-title'>$headline        
          $stars
          </h5>
          <p class = 'card-text'>
          $cityID
          $personIcons
          $propType
          <span><i class='fa fa-bed'></i></span>
          $beds Bed(s)
          <span><i class='fa fa-bath'></i></span>
          $bathrooms Bathroom(s)
          </p> 
          <p class = 'card-text'> <span style = 'color:green'>$cancellationPolicy Cancellation</span></p>
          <p class='card-text'>Starting from <strong>$$price</strong></p>";
            if($_SESSION['admin'] == 1){//admin is logged in give ability to edit
              echo "
              <form method = POST action = 'editproperty.php' style = 'display:inline'>
                <input type = 'hidden' name = 'prevurl' value = {$_SERVER['REQUEST_URI']}>
                <button type = 'submit' class='btn btn-primary btn-md' name = 'prop' value = $PropertyID>Edit Listing</button>
              </form>
              <form method = 'POST' action = 'deleteproperty.php' style = 'display:inline'>
                <input type = 'hidden' name = 'propID' value = $PropertyID>
                <button type='submit' name = 'prevurl' value = {$_SERVER['REQUEST_URI']} class='btn btn-primary btn-md'>Delete Listing</button>
              </form>";
              }
            echo"
          </div>
        </div>
    </div>
    </a>
    </div>
    
  ";
  }
  echo "</div>";//close the row


?>


    
  </section>

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
            echo "<p>You need to be logged in as a user to use the like feauture !</p>
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
  <script>
  (function () {
  'use strict'

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  var forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }

        form.classList.add('was-validated')
      }, false)
    })
})()

    </script>



  
</body>
</html>