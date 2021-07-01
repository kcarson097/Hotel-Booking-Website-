<?php
    // //this is needed so that the user can go back to the prev page from other pages and it will remember the previous search by the user
    // header('Cache-Control: no cache'); 
    // session_cache_limiter('private_no_expire'); 

    session_start();

    if($_SESSION['login'] == 0 || $_SESSION['admin'] == 1){//user is not authorised for this page redirect them !
      header("Location: index.php");
    }

    //print_r($_SESSION);

    if(!isset($_GET['page'])){
      $page = 1;
    }else{
      $page = $_GET['page'];
    }

    $endpoint = "http://kcarson09.lampt.eeecs.qub.ac.uk/mybnbapi/accounts/favourites.php?userID={$_SESSION['userID']}&page={$page}";
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

    <title>MyFavourites</title>
 
</head>


<body>



</body>

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
                }else{
                  echo "    <li class='nav-item active'>
                              <a class='nav-link' href='myfavourites.php'>My Favourites</a>
                            </li>
                            <li class='nav-item active'>
                              <a class='nav-link' href='mybookings.php'>My Bookings</a>
                            </li><li class='nav-item active'>
                            <a class='nav-link' href='loggedout.php'>Logout</a>
                            </li>
                        ";
                }
        ?>
      </ul>
    </div>
  </nav>

</section>

<section id = main>
      
      <div class="row">
      <div class="col-md-1"></div>
      <div class="col-md-10">
  
      <div>
          <h3 class = "titlesindex"><?php echo"{$_SESSION['username']}"?>,Showing your favourites : </h3>
      </div>
      <?php
      //check if there are items in array- the lentth will always be one as the api returns the number of results for the first index
        if(count($data)<=1){
          
          echo "<div><h3 class ='titlesindex'>No Results Found</h3><a href = 'index.php'>Return home</a></div>";
        }else{
          $numberpages = ceil($numberOfResults/20);
         
        echo "<div>
        <h3 class = 'titlesindex'>Page $page of $numberpages - $numberOfResults results found </h3>
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
          $bathrooms = $data[$index]["NumberOfBathrooms"];
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
                  <img class='card-img-top' src = '$thumbnailurl'>
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
                    <p class='card-text'>Starting from <strong>$$price</strong></p>
                    <form method = POST action = 'removefavourite.php'>
                      <input type = 'hidden' name = 'prevurl' value = {$_SERVER['REQUEST_URI']}>
                      <button type = 'submit' class='btn btn-primary btn-md' name = 'prop' value = $PropertyID>Delete Favourite</button>
                    </form>
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
            <img class='card-img-top' src = '$thumbnailurl'>
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
                <p class='card-text'>Starting from <strong>$$price</strong></p>
                <form method = POST action = 'removefavourite.php'>
                  <input type = 'hidden' name = 'prevurl' value = {$_SERVER['REQUEST_URI']}>
                  <button type = 'submit' class='btn btn-primary btn-md' name = 'prop' value = $PropertyID>Delete Favourite</button>
                </form>
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

<?php
    //build pagination
    $num = rand(1,100000);      
    echo "
          <ul class='pagination d-flex justify-content-center'> 
              <li class='page-item'>
              <a class='page-link' href='myfavourites.php?page=1&rand=$num'>First</a>
              </li>";
      
      $numberpages = ceil($numberOfResults/20); //calculate the total number of pages
      $maxPages = 5; //max number of pagination pages shown
      $startPage = max($page,$page - $maxPages+1); //the current page the user is on
      $endpage = min($numberpages,$page+$maxPages); //the last page link to be shown for that instance
      
      for($i=$startPage;$i<=$endpage;$i++){//generate links to page from start to end page
        echo "<li class='page-item'><a class='page-link' href='myfavourites.php?page=$i&rand=$num'>$i</a></li>";
      }
      echo "<a class='page-link' href='myfavourites.php?page=$numberpages&rand=$num'>Last</a>";
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


</body>
</html>