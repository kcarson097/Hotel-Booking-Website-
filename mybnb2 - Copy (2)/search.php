<?php
      //this is needed so that the user can go back to the prev page from other pages and it will remember the previous search by the user
      header('Cache-Control: no cache'); 
      session_cache_limiter('private_no_expire'); 
      //ini_set('session.cookie_secure',1);

      session_start();
      //print_r($_SESSION);

     
      if(!isset($_SESSION['login'])){
        $_SESSION['login'] = 0;
        $_SESSION['userID'] = "";
        $_SESSION['username'] = "";
        $_SESSION['admin'] = 0;
      }else{
        $login = $_SESSION['login'];
        $user = $_SESSION['userID'];
        $username = $_SESSION['username'];
        $admin = $_SESSION['admin'];
      }

          
      //print_r($_SESSION);

    
       //check if the page is opened from a search using the search bar
       if(isset($_POST['citybar'])){
         
          $city = $_POST['citybar'];
          $guests = $_POST['guestbar'];
          //echo $city
          session_unset();
          //store these vars in a session so that they can be used for pagination searches
          $_SESSION['mybnbcity'] = $city;
          $_SESSION['mybnbguests'] = $guests;

       }

       
      if(isset($_GET['location'],$_GET{'accomodates'})){//if the user selects the city options from footer !
        session_unset();
        $city = $_GET['location'];
        $guests = $_GET['accomodates'];
        
        $_SESSION['mybnbcity'] = $city;
        $_SESSION['mybnbguests'] = $guests;

      }

      $_SESSION['login'] = $login;
      $_SESSION['userID'] = $user;
      $_SESSION['username'] = $username;
      $_SESSION['admin'] = $admin;
      print_r($_SESSION);
   
       if(!isset($_GET['page'])){
         $page = 1;
       }else{
         $page = $_GET['page'];
       }
       
       //$city = $_SESSION['mybnbcity'];
       //$guests = $_SESSION['mybnbguests'];

     
      
        //connect to api and get the appropriate results returned
        $endpoint = "http://kcarson09.lampt.eeecs.qub.ac.uk/mybnbapi/searches/searchapi.php?location={$_SESSION['mybnbcity']}&accomodates={$_SESSION['mybnbguests']}&page={$page}";
       
        $result = file_get_contents($endpoint);
        $data = json_decode($result,true);
   
        //this is needed to calculate number of pages to show for pagination
        $numberOfResults = $data["Number of results"];
       

    

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script
  src="https://code.jquery.com/jquery-3.6.0.js"
  integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
  crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
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
    <div class="jumbotron two jumbotron-fluid">
      <div class="container">
      <h1 class="display-4 text2"><span>You are only one click away....</span></h1>
      </div>
      </div>

<section id = main>
  <!--Filters-->
  <div class="row">
  <div class="col-md-2 filters">
      <button type="button" data-toggle="collapse" data-target="#filters" class="d-block d-md-none btn btn-primary btn-block mb-3">Filters &dtrif;</button>
      <div id = "filters" class = "d-md-block collapse">
        
        <div class="card">
          <div class="card-body ">
            <h5 class = "filtertitles">Filter by:</h5>
          </div>
        </div>
        <form method = "POST" action = 'filtersearch.php' name = "filter">
        <div class="card">
          <div class="card-body">
            <p class = "filtertitles">Property Type</p>
        
        <?php
            //connect to api and get all the property types
            $endpointprops = "http://kcarson09.lampt.eeecs.qub.ac.uk/mybnbapi/searches/propertytype.php?propertytype";
            $resultprops = file_get_contents($endpointprops);
            $dataprops = json_decode($resultprops,true);

            //loop trhough and add to filters - show 15 options and then have a show more button 
            for($i=0;$i<15;$i++){
              $id = $dataprops[$i]["PropertyTypeID"];
              $name = $dataprops[$i]["Name"];
              echo " <div class='form-check' name = 'proptype[]'>
                        <input class='form-check-input' type='checkbox' value='$id' id='defaultCheck1' name = 'proptype[]'>
                        <label class='form-check-label' for='defaultCheck1'>
                        $name
                      </label>
                    </div>";
            }
            //show more button
            echo " <a data-toggle='collapse' href='#collapsefilters' role='button' aria-expanded='false' aria-controls='collapsefilters'>
                      <i class='fa' aria-hidden='true'></i>
                      Show More
                    </a>";
            //show rest of options under show more
            for($i=15;$i<count($dataprops)-1;$i++){
              $id = $dataprops[$i]["PropertyTypeID"];
              $name = $dataprops[$i]["Name"];
            echo "  <div class='collapse' id='collapsefilters'>
                      <div class = 'form-check'>
                        <input class='form-check-input' type='checkbox' value='$id' id='defaultCheck1' name = 'proptype[]'>
                        <label class='form-check-label' for='defaultCheck1'>
                          $name
                        </label>
                      </div>
                    </div>";
            }
        ?>
          </div>
        </div>
           
       
      <div class="card">
        <div class="card-body">
          <p class = "filtertitles">Ammenities</p>
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
          
  
    </div>
   
 
   

  <div class="card">
    <div class="card-body">
      <p class = "filtertitles">Booking Options</p>
      <?php
          //connect to api and get all the amenities
          $endpointpolicy = "http://kcarson09.lampt.eeecs.qub.ac.uk/mybnbapi/names/cancelpolicies.php"; 
          $resultpolicy = file_get_contents($endpointpolicy);
          $datapolicy = json_decode($resultpolicy,true);
        foreach($datapolicy as $row){
          $id = $row["PolicyID"];
          $name = $row["Name"];
          echo" <div class='form-check name = cancelpolicies[]'>
          <input class='form-check-input' type='checkbox' value='$id' id='defaultCheck1' name = cancelpolicy[]'>
          <label class='form-check-label' for='defaultCheck1'>
            $name
          </label>
          </div>";
        }
     
      
      ?>
       
    </div>
  </div>

  <div class="card">
    <div class="card-body ">
    <button type="submit" id = "filterbutton" value = "submit" class="btn btn-info" >Apply Filters</button>
    </div>
  </div>
 
</div>

  </div>
  </div>
  </form>


<div class="col-md-9">


          <form method = "POST" action = 'search.php'>
          <div class="form-group searchpagebar needs-validation"novalidate>
          
          <span>
              <select class="selectpicker form" name = 'citybar' data-width = '260px' data-live-search = "true"  searchable="Search here.."required>
                  <option class = "special" value="" disabled selected>Where to ?</option>
                  <?php
                      //call api to get all the cities in the db
                      $endpoint = "http://kcarson09.lampt.eeecs.qub.ac.uk/mybnbapi/names/cityNames.php";
                      $result2 = file_get_contents($endpoint);
                      $citydata = json_decode($result2,true);
                      foreach($citydata as $row){
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
                  <button type="submit" value = "submit" class="btn btn-info">  
                    <span class="glyphicon glyphicon-search"></span> Search
                  </button>
        </span>
        </div>	
        </form>
  <?php
        //check if there are items in array- the lentth will always be one as the api returns the number of results for the first index
        if(count($data)<=1){
          
          echo "<div><h3 class ='titlesindex'>No Results Found</h3></div>";
        }else{
          $numberpages = ceil($numberOfResults/20);

        echo "<div>
        <h3 class = 'titlesindex'>Page $page of $numberpages - $numberOfResults results found </h3>
        </div>";

        //sort by buttons - the formaction directs to the appropriate page 

        echo "<div class = row'>
              <div class = col-md-9>
             
                <form method = 'POST' action = 'sortsearch.php'> 
                  <div class='btn-group sortbuttons' role='group' aria-label='Basic example'> 
                    <button type='submit' class='btn btn-primary disabled'>Sort By :</button>
                    <button href = 'sortsearch.php' type='submit' value = 'Rating' name = 'sortby' class='btn btn-outline-primary'>Rating (Highest First)</button>
                    <button href = 'sortsearch.php' type='submit' value = 'Price' name = 'sortby' class='btn btn-outline-primary'>Price (Lowest First)</button>
                    <button href = 'sortsearch.php' type='submit' value = 'NumberOfLikes' name = 'sortby' class='btn btn-outline-primary'>Most Popular</button>
                  </div>
                  </form>
                  </div>
              </div>";
  
  	

    
        $count = 0; //count of propery cards created - needed to create a new row after every four
        //echo $count;
        for($index = 0;$index<count($data)-1;$index++){
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

          
          if($count % 4 == 0){//create a new row
            echo "
            <div class='row cards'>
              <div class='col-sm'>
                <div class='card h-100'>
                <div class='container2'>
                  <img class='card-img-top' src = '$thumbnailurl'> ";
                  //user is logged out need them to login to use like feauture
                  if($_SESSION['login'] == 0 || $_SESSION['admin'] == 1){
                    echo "<button type='button' class = 'btn3' data-toggle = 'modal' data-target='#likemodal'><i class='fa fa-heart-o' aria-hidden='true'></i></button>";                  
                  }
                  else{
                    echo "<form method = POST action = 'addFavourite.php'>
                          <input type = 'hidden' name = 'prevurl' value = {$_SERVER['REQUEST_URI']}>
                          <button type='submit' value = $PropertyID name = 'fav' class='btn3'><i class='fa fa-heart-o' aria-hidden='true'></i></button>
                          </form>";
                  }
                  echo"
                  </div>
                 
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
                        </form>";
                      }
                    echo"

                  </div>
                </div>
              </div>
            </a>";
          $count++;
          //echo "$count";
          }else{
            echo" 
            <div class='col-sm'>
            <div class='card h-100'>
            <div class='container2'>
            <img class='card-img-top' src = '$thumbnailurl'>";
            if($_SESSION['login'] == 0 || $_SESSION['admin'] == 1){
              echo "<button type='button' class = 'btn3' data-toggle = 'modal' data-target='#likemodal'><i class='fa fa-heart-o' aria-hidden='true'></i></button>";                  
            }
            else{
              echo "<form method = POST action = 'addFavourite.php'>
                    <input type = 'hidden' name = 'prevurl' value = {$_SERVER['REQUEST_URI']}>
                    <button type='submit' value = $PropertyID name = 'fav' class='btn3'><i class='fa fa-heart-o' aria-hidden='true'></i></button>
                    </form>";
            }
            echo"
            </div>
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
        ";
      
        $count++;
        //echo "$count"
        }
        if(($count)%4==0){
          echo "</div>";//close the row
        }
        
      }
        echo "</div></div></div>"; //close the column
        }

        ?>

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
    
    <?php
    //build pagination   
    $num = rand(1,100000);       
    echo "
          <ul class='pagination d-flex justify-content-center'> 
              <li class='page-item'>
              <a class='page-link' href='search.php?page=1&rand=$num'>First</a>
              </li>";
      
      $numberpages = ceil($numberOfResults/20); //calculate the total number of pages
      $maxPages = 5; //max number of pagination pages shown
      $startPage = max($page,$page - $maxPages+1); //the current page the user is on
      $endpage = min($numberpages,$page+$maxPages); //the last page link to be shown for that instance
      
      for($i=$startPage;$i<=$endpage;$i++){//generate links to page from start to end page
        echo "<li class='page-item'><a class='page-link' href='search.php?page=$i&rand=$num'>$i</a></li>";
      }
      echo "<a class='page-link' href='search.php?page=$numberpages&rand=$num'>Last</a>";
      echo"</ul></section>"; //close list
    
    
  ?>

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