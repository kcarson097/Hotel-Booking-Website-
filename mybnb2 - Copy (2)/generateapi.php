<?php
    session_start()
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
    <title>LogIn</title>
    
</head>


<body>

  <section class = "header">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary py-2">
      <a class="navbar-brand" href="index.php">MyBnb</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    </nav>

  </section>


  <section id = "main">

   <div class = "login">

   <div class="container">
	<div class="d-flex justify-content-center h-100 logincard">
		<div class="card logincard">
			<div class="card-header loginheader">
				<h3>Get API Key</h3>
			</div>
        <div class = "card-body">
        <form method = "POST" action = ""> 
        <div class="form-group ">
            <label class = "labels" for="exampleInputEmail1">Username</label>
            <div class = "inner-addon left-addon">
                    <i class="fa fa-user" aria-hidden="true"></i>
                    <input type="text" class="form-control" name = 'username' aria-describedby="emailHelp" placeholder="Enter Username"required>
            </div>
            <label class = "labels" for="exampleInputEmail1">Email</label>
            <div class = "inner-addon left-addon">
                    <i class="fa fa-user" aria-hidden="true"></i>
                    <input type="email" class="form-control" name = 'email' aria-describedby="emailHelp" placeholder="Enter Email"required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Get Key</button>
        <?php
            if(isset($_POST['username'])){//i.e the user has submitted their credientials
                  //check if username already exists
                  $username = urlencode($_POST['username']);
                  $email = urlencode($_POST['email']);
                  $endpoint = "http://kcarson09.lampt.eeecs.qub.ac.uk/mybnbapi/apisecurity/checkuserexists.php?username={$username}";
                  $result = file_get_contents($endpoint);
                  $data = json_decode($result,true);
                  $numberOfResults = $data["Number of results"];
                  if($numberOfResults >0){
                    echo "<p class = 'createaccounttextfail'>Username already exists</p>";
                  }else{
                    //insert new user and generate their api key
                    $endpoint = "http://kcarson09.lampt.eeecs.qub.ac.uk/mybnbapi/apisecurity/generatekey.php?username={$username}&email={$email}";
                    echo $endpoint;
                    $result = file_get_contents($endpoint);
                    $data = json_decode($result,true);
                    $key = $data['Key'];
                    echo "<p class = 'createaccounttextsuc'>Account Created, your key is : $key</p>";
                }
              }

        ?>
        </form>
        </div>
        
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
                    <p1><a class = 'links' href = 'createaccount.php'>Create Account</a></p1>
                  </div>
                  <div>
                    <p1><a class = 'links' href = 'login.php'>Sign In</a></p1>
                  </div>
                </div>";
        }else if($_SESSION['admin'] == 1){
          echo "<div>
                  <p1><a class = 'links' href = 'createlisting.php'>Create Listing</a></p1>
                </div>
                <div>
                    <p1><a class = 'links' href = 'loggedout.php'>Logout</a></p1>
                  </div>
              </div>";
        }else{
          echo " <div>
                    <p1><a class = 'links' href = 'myfavourites.php'>View My Favourites</a></p1>
                  </div>
                  <div>
                    <p1><a class = 'links' href = 'mybookings.php.php'>View My Bookings</a></p1>
                  </div>
                  <div>
                    <p1><a class = 'links' href = 'loggedout.php'>Logout</a></p1>
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