<?php
require 'startup.php';
  
  // Unset all of the session variables
  $_SESSION = array();
  
  // Destroy the session.
  session_destroy();
?>

<!DOCTYPE html>

<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Family Application to increase communication and bonding - Home Page"/>

  <title>Family Favorites</title>

    <!-- CSS Stylesheets -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/styles.css" />
  <link rel="stylesheet" type="text/css" href="css/navbar.css" />
  <link rel="stylesheet" type="text/css" href="css/get-a-quote-form.css" />

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Great+Vibes|Lato|Raleway" rel="stylesheet">

  <!-- Favicon -->
  <link rel="icon" href="images/coffee-website-favicon.jpg">
  <!--style>
    div.topbanner {
    position: -webkit-sticky;
    position: sticky;
    top: 0;
  }
  </style-->
</head>

<body>

<?php 
  //Testing Console_Log
  //console_log("Index Page Loading");
  //console_log($_SESSION); 
?>

  <div class="topbanner" id="nav-sect">
    <nav class="navbar navbar-default navbar-static navbar-transparent" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand" href="/index.php">
            <img alt="Brand" src="images/coffee-shop-logo.png" class="logo">
          </a>
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=" .navbar-collapse">
            <span class="sr-only">Toggle navigation </span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div><!--end navbar-header-->
        <div class="collapse navbar-collapse" align="center">
          <ul class="nav navbar-nav">          
          <?php 
              if($_SESSION["username"] == "juaneric") {
                echo '<li id="admin" align="center"><a href="/registration.php">Registration</a></li>';
                echo '<li id="admin" align="center"><a href="/login.php">Login</a></li>';
                echo '<li id="admin" align="center"><a href="/dashboard.php">Dashboard</a></li>';
                echo '<li id="admin" align="center"><a href="/contact.php">Contact</a></li>';
                echo '<li id="admin" align="center"><a href="/debug_out.php">Debug Out</a></li>';
                echo '<li id="admin" align="center"><a href="/logout.php">Logout</a></li>';
              } else {
                echo '<li align="center"> <a href="/login.php">Login</a></li>';
                echo '<li align="center"> <a href="/contact.php">Contact Me</a></li>';
              }
            ?>
          </ul>
        </div><!--end navbar-collapse-->
      </div><!--end container-->
    </nav><!--end nav-->
    <h1 align="center">Johnson-Thomas Estate<br /><span class="cursive">Favorite Things Project</span></h1>
  </div><!--end topbanner-->

  <div id="morecustomers">
    <div class="container">
      <h3 align="center">Some Favorites</h3>
      <div class="row">
        <div class="col-md-4" align="center">
          <h2>Books</h2>
          <p>Reading takes you on a special journey through the soul.</p>
        </div><!--end col-md-4-->
        <div class="col-md-4" align="center">
          <h2>Quotes</h2>                
          <p>Our daily mantras to help us manage and prosper in life.</p>
        </div><!--end col-md-4-->
        <div class="col-md-4" align="center">
          <h2>Social Media</h2>
          <p>Keeping in touch with friends and family around the world.</p>
        </div><!--end col-md-4-->
      </div><!---end /.row-->
    </div><!-- en /.container-->
  </div><!--- /.morecustomers-->

  <div id="login-stat-sect" class="exclusive-group" align="center">
    <p>
      <?php
        if ($_SESSION["loggedin"]) {
          echo "You are Logged In as: " . $_SESSION["username"];
        }
        else {
          echo "Log in to Enter your Favorites";
        }
      ?>  
      <!-- <a href="#"><span style="color: white">Link</span></a> -->
    </p>
  </div><!--end exclusive-group-->

  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>

</body>
</html>
