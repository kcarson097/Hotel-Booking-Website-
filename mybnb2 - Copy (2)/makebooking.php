<?php

session_Start();

if($_SESSION['login'] == 0 || $_SESSION['admin'] == 1){//user is not authorised for this page redirect them !
  header("Location: index.php");
}

if(isset($_POST['propID'],$_POST['prevurl'])){
    $propID = $_POST['propID'];
    $prevurl = $_POST['prevurl'];

    $_SESSION['propIDbooking'] = $propID;
}

$currentdate = date('Y-m-d');





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
  
    <title>Make Booking</title>
    
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


   <div class = "createaccount">

   <div class="container">
	<div class="d-flex justify-content-center logincard">
		<div class="card logincard">
			<div class="card-header loginheader">
				<h3>Make Booking</h3>
			</div>
        <div class = "card-body">
        <form method = "POST" action = "" >
        <div class="form-group needs-validation" novalidate>
          <label class = 'logouttext' >Arrival Date</label>
          <?php echo "<input type='date' name='startdate' max='3000-12-31' min = '{$currentdate}' class='form-control' required>"   ?>
        </div>
        <div class="form-group ">
          <label class = 'logouttext'>Departure Date</label>
          <?php echo "<input type='date' name='enddate' max='3000-12-31' min = '{$currentdate}' class='form-control' required>"   ?>
        </div>
        <button type="submit" value = "submit" class="btn btn-primary">Make Booking</button>
        <?php
            if(isset($_POST['startdate'])){//i.e form has been submitted
              //connect to api and see if there are any overlapping bookings for this proeprty
              $start = $_POST['startdate'];
              $end = $_POST['enddate'];
              if(strtotime($start)<=strtotime($end)){//check if start date is before end date !
              $endpoint = "http://kcarson09.lampt.eeecs.qub.ac.uk/mybnbapi/bookings/checkavailability.php?propID={$_SESSION['propIDbooking']}&startdate={$start}&enddate={$end}";
              $resultExists = file_get_contents($endpoint);
              $dataExists = json_decode($resultExists,true);
              if($dataExists['Number of results'] == 0){//no overlapping bookings
                  //add booking to database and confirm to user
                  $insert = "http://kcarson09.lampt.eeecs.qub.ac.uk/mybnbapi/bookings/createbooking.php?propID={$_SESSION['propIDbooking']}&startdate='{$start}'&enddate='{$end}'&userID={$_SESSION['userID']}";
                  $result = file_get_contents($insert);
                   //give user to go back or to view all their bookings
                   echo "<p class = 'createaccounttextsuc'>
                   Booking Successful
                   <a class = 'createaccounttextsuc' href = 'index.php'>&nbspClick here to go home</a>
                   </p>
                   <a href = mybookings.php class = 'createaccounttextsuc'>&nbspView All Bookings</a>";
              }else{//overlapping bookings
                echo "<p class = 'createaccounttextfail'>The property is not available on these dates ! Try again</p>
                <a class = 'logouttext' href = 'index.php'>&nbspClick here to go home</a>";
              }
            }else{
              echo "<p class = 'createaccounttextfail'>Invalid Dates ! Try again</p>";
            }
          }
        ?>
        </div>
        </form>
       
        </div>
</div>







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


  <script >

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











?>