<?php

session_start();

if($_SESSION['login'] == 0 || $_SESSION['admin'] == 0){//user is not authorised for this page redirect them !
  header("Location: index.php");
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

  <section class = 'main'>


  <div class = 'row'>
  <div class = col-sm-2></div>
  <div class = col-sm-9>

  <div>
      <h1><span>Create New Listing : </span></h1>
  </div>
  <form method = POST action = 'listingcreated.php'>
  <div class = 'form-group needs-validation'novalidate>
  <label>Property Type</label>
  <select class="form-control" id="exampleFormControlSelect1" name = 'propType'required>
  <option value="" disabled selected></option>
  <?php
  //select property type
  $endpoint = "http://kcarson09.lampt.eeecs.qub.ac.uk/mybnbapi/searches/propertytype.php?propertytype";//returns all the prop types available
  $resultprops = file_get_contents($endpoint);
  $dataprops = json_decode($resultprops,true);
    //loop through results and add to dropdown list
    for($i=0;$i<count($dataprops);$i++){
    $id = $dataprops[$i]["PropertyTypeID"];
    $name = $dataprops[$i]["Name"];
    echo " <option value = $id class = 'special' >$name</option>";
  }
  ?>
  </select>
  <label>Room Type</label>
  <select class="form-control" id="exampleFormControlSelect1" name = 'roomtype'required>
  <option value="" disabled selected></option>
  <?php
  //select room type
  $endpoint = "http://kcarson09.lampt.eeecs.qub.ac.uk/mybnbapi/admin/listingoptions.php?roomtype";//returns all the room types available
  $resultprops = file_get_contents($endpoint);
  $dataprops = json_decode($resultprops,true);
    //loop through results and add to dropdown list
    for($i=0;$i<count($dataprops);$i++){
    $id = $dataprops[$i]["RoomTypeID"];
    $name = $dataprops[$i]["Name"];
    echo " <option value = $id class = 'special' >$name</option>";
  }
  ?>
  </select>
  <label>City</label>
  <select class="form-control" id="exampleFormControlSelect1" name = 'city'required>
  <option value="" disabled selected></option>
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
  <label>Host</label>
  <select class="form-control" id="exampleFormControlSelect1" name = 'host'required>
  <option value="" disabled selected></option>
  <?php
  //select all hosts
    $endpoint = "http://kcarson09.lampt.eeecs.qub.ac.uk/mybnbapi/admin/listingoptions.php?hosts";//returns all the room types available
    $resultprops = file_get_contents($endpoint);
    $dataprops = json_decode($resultprops,true);
    //loop through results and add to dropdown list
    for($i=0;$i<count($dataprops);$i++){
    $id = $dataprops[$i]["HostID"];
    $name = $dataprops[$i]["HostName"];
    echo " <option value = $id class = 'special' >$name ($id)</option>";
  }
  ?>
  </select>
  <label for="exampleFormControlInput1">Headline</label>
  <input type="text" class="form-control" name = 'headline'required>
  
  <label for="exampleFormControlInput1">Description</label>
  <textarea class="form-control" name = 'description'required></textarea>
  
  <label for="exampleFormControlInput1">Accomodates</label>
  <input type="number" class="form-control" name = 'accomodates'>
  
  <label for="exampleFormControlInput1">Number Of Reviews</label>
  <input type="number" class="form-control" name = 'reviews'>

  <label>Cancellation Policy</label>
  <select class="form-control" id="exampleFormControlSelect1" name = 'cancelpolicy'required>
  <option value="" disabled selected></option>
  <?php
  //select cancel policies
    $endpoint = "http://kcarson09.lampt.eeecs.qub.ac.uk/mybnbapi/admin/listingoptions.php?cancelpolicies";//returns all the room types available
    $resultprops = file_get_contents($endpoint);
    $dataprops = json_decode($resultprops,true);
    //loop through results and add to dropdown list
    for($i=0;$i<count($dataprops);$i++){
    $id = $dataprops[$i]["PolicyID"];
    $name = $dataprops[$i]["Name"];
    echo " <option value = $id class = 'special' >$name</option>";
  }
  ?>
    </select>


<label>Rating</label>
<select class="form-control" id="exampleFormControlSelect1" name = 'rating'required>
  <option value = 1 class = 'special'>1</option>
  <option value = 2 class = 'special'>2</option>
  <option value = 3 class = 'special'>3</option>
  <option value = 4 class = 'special'>4</option>
  <option value = 5 class = 'special'>5</option>
</select>

<label for="exampleFormControlInput1">Zip Code</label>
<input type="text" class="form-control" name = 'zipcode'required>

<label for="exampleFormControlInput1">Price Per Night (Rounded Price)</label>
<input type="number" class="form-control" name = 'price'required>

<label for="exampleFormControlInput1">Number of Beds</label>
<input type="number" class="form-control" name = 'beds'required>

<label for="exampleFormControlInput1">Number of Bedrooms</label>
<input type="number" class="form-control" name = 'bedrooms'required>

<label for="exampleFormControlInput1">Number of Bathrooms</label>
<input type="number" class="form-control" name = 'bathrooms'required>

<label for="exampleFormControlInput1">thumbnail URL</label>
<input type="text" class="form-control" name = 'thumbnail'required>

<label for="exampleFormControlInput1">Main Image</label>
<input type="text" class="form-control" name = 'proppageimage'required>

<label for="exampleFormControlInput1">Ammenities</label>
<?php
      //connect to api and get all the amenities
      $endpointams = "http://kcarson09.lampt.eeecs.qub.ac.uk/mybnbapi/names/amenities.php?amenities"; 
      $resultams = file_get_contents($endpointams);
      $dataams = json_decode($resultams,true);

      //loop trhough and add to filters - show 15 options and then have a show more button 
      for($i=0;$i<15;$i++){
        $id = $dataams[$i]["AmenityID"];
        $name = $dataams[$i]["Name"];
        echo " <div class='form-check' name = 'amenities'>
                  <input class='form-check-input' type='checkbox' value='$id' id='defaultCheck1' name = 'amenities[]'>
                  <label class='form-check-label' for='defaultCheck1'>
                  $name
                </label>
              </div>";
      }
      //show more button
      echo " <a data-toggle='collapse' href='#collapsefiltersams' role='button' aria-expanded='false' aria-controls='collapsefiltersams'>
                <i class='fa' aria-hidden='true'></i>
                Show More
              </a>";

      //show rest of options under show more
      for($i=15;$i<count($dataams)-1;$i++){
        $id = $dataams[$i]["AmenityID"];
        $name = $dataams[$i]["Name"];
      echo "  <div class='collapse' id='collapsefiltersams'>
                <div class = 'form-check'>
                  <input class='form-check-input' type='checkbox' value='$id' id='defaultCheck1' name = 'amenities[]'>
                  <label class='form-check-label' for='defaultCheck1'>
                    $name
                  </label>
                </div>
              </div>";
      }
    ?>


<div class = 'listingbtn'>
<button type="submit"class='btn btn-primary btn-md' value = "submit" >Create Listing</button>
</div>
  </form>
  </div>
  </div>
  </section>


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