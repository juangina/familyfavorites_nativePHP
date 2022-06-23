<?php
  require 'startup.php';

  // Check if the user is not logged in, redirect him to login page
  if($_SESSION["loggedin"] === NULL) {
    header("location: login.php");
    exit;
  }

  /* Database credentials */
  if ($local == True) {
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'postgres');
    define('DB_PASSWORD', 'mySQLdb03');
    define('DB_NAME', 'postgres');
  }
  else {
    $db = parse_url(getenv("DATABASE_URL"));
  }

  /* Attempt to connect to MySQL database */
  try {
    if($local == True) {
      $pdo = new PDO("pgsql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
    }
    else {
      $pdo = new PDO("pgsql:" . sprintf(
        "host=%s;port=%s;user=%s;password=%s;dbname=%s",
        $db["host"],
        $db["port"],
        $db["user"],
        $db["pass"],
        ltrim($db["path"], "/")
      ));
    }

    $mssg .= "Defining Variables.";

    $mssg .= "New Login Object Created.";
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $mssg .= "Setting Login Default Attributes.";
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $mssg .= "Connection to Database Made.";
  }
  catch(PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
    $mssg .= "Error, could not connect.";
  }

  //Query the db for favorite topics for the dropdown list
    $sql = "SELECT * FROM favorite_topics";
    if($stmt = $pdo->prepare($sql)) {    
        if($stmt->execute()) {  
            if($debug1 = $stmt->rowCount()) { 
                while ($row = $stmt->fetch()) {
                    $opts[] = [
                        'topic'=>$row['topic'],
                        'description'=>$row['description'],
                        'recommended_user'=>$row['recommended_user'],
                        'comments'=>$row['comments']
                    ];
                }
                //print_r($opts);
            }else{$debug2 = "stmt->rowCount error";}
        }else{$debug2 = "stmt->execute error";}
    }else{$debug2 = "pdo->prepare error";}
  //end Query the db for favorite topics for the dropdown list
  
  //Setup URL to Query API for random quote
    $url_forismatic = 'https://api.forismatic.com/api/1.0/';
    $forismatic_query_fields = [
            'method' => 'getQuote',
            'lang' => 'en',
            'format' => 'json',
            'jsonp' => '?'
    ];
  //end Setup URL to Query API for random quote

  //Send API GET request for random quote 
    $curl = curl_init($url_forismatic . '?' . http_build_query($forismatic_query_fields));           
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $rawJSONResponse = curl_exec($curl);        
    $response = json_decode($rawJSONResponse, true); 
  //end Send API GET request for random quote


  if ($_POST["submit"]) {
    //$_SESSION["debug4"] = $_POST;

    if (!$_POST['topic']) {
      $error="<br />Please select a topic.";
    }

    if (!$_POST['favorite']) {
      $error.="<br />Please enter your favorite for the topic.";
    }
    if (!$_POST['comment']) {
      $error.="<br />Please add a simple comment.";
    }    

    if ($error) {
      //Error in form
      $mssg = "Tranaction completed";
      $_SESSION["debug3"] = $mssg;
    } 
    else {
      /* Form Data has been validated */
      //Save Form Data for Processing
      $topic = $_POST['topic'];
      $favorite = $_POST['favorite'];
      $comment = $_POST['comment'];
      //Clear Data from Form
      $_POST['topic'] = "";
      $_POST['favorite'] = "";
      $_POST['comment'] = "";


      /* SAVE THE REGISTRATION INFORMATION IN THE DATABASE */
      $sql = "INSERT INTO favorites(username, topic, favorite, comments) VALUES (:username, :topic, :favorite, :comment)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(['username'=> $_SESSION["username"],'topic'=>$topic,'favorite'=>$favorite,'comment'=>$comment]);
      $mssg .= "Favorite Added";
      
      // Close connection
      unset($pdo);
      $mssg = "Tranaction completed";
      $_SESSION["debug3"] = $mssg;
      header("location: dashboard.php");
    }
  }
 
?>

<!DOCTYPE html>

<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Coffee shop growth: websites, SEO, social media and online marketing."/>

  <title>Family Favorites</title>

    <!-- CSS Stylesheets -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/style.css" />
  <link rel="stylesheet" type="text/css" href="css/navbar.css" />
  <link rel="stylesheet" type="text/css" href="css/get-a-quote-form.css" />

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Great+Vibes|Lato|Raleway" rel="stylesheet">

  <!-- Favicon -->
  <link rel="icon" href="images/coffee-website-favicon.jpg">
</head>

<body>
<?php 
    //Testing Console_Log
    //console_log("Dashboard Page Loading");
    //console_log($_SESSION); 
  ?>
  <div class="topbanner">
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
                echo '<li align="center"> <a href="/logout.php">Logout</a></li>';
                echo '<li align="center"> <a href="/contact.php">Contact Me</a></li>';
              }
            ?>
          </ul>
        </div><!--end navbar-collapse-->
      </div><!--end container-->
    </nav><!--end nav-->
    <h1 align="center">Johnson-Thomas Estate<br /><span class="cursive">Favorite Things Project</span></h1>
  </div><!--end topbanner-->

  <div class="testimonials"> 
    <h4 align="center">Welcome to the Family Favorites Page!</h4>
  </div>  <!--- /.testimonials-->

  <div class="contactusform">
    <div class="row">
      <div class="col-md-6 contact-details" align="center"><br/>
        <div class="contact-info bg-info">
          <h4 class="contact-heading">Favorite Options for the week</h4>
          <!--p class="info"><strong>Book</strong> - Reading takes you on a special journey through the soul.</p>
          <p class="info"><strong>Quote</strong> - Our daily mantras to help us manage and prosper in life.</p>
          <p class="info"><strong>Social</strong> - Keeping in touch with friends and family around the world.</p-->
          <?php foreach ($opts as $opt) : ?>
            <p class="info"><strong><?php echo htmlspecialchars($opt['topic'])?></strong><?php echo " - " . htmlspecialchars($opt['description']);?>
            </p>
          <?php endforeach; ?>
        </div><!--end contact-info-->
        <div class="contact-info bg-danger"  style="margin-top: 150px">
          <h4 class="contact-heading">Interesting Quote</h4>
          <blockquote class="blockquote-reverse">
            <?php
              if (!empty($response)) {
                    echo "<p style='font-style: italic'>" . $response["quoteText"] . "</p>";
                    echo "<footer>" . $response["quoteAuthor"] . "</footer>";
                    //echo "<a href=" . $response['quoteLink'] . "target='_blank'>" . $response['quoteLink'] . "</a>" . "</br>";                        
              }
            ?>
          </blockquote>
        </div><!--end contact-info-->
      </div><!--end contact-details-->
      <div class="col-md-6 emailForm" align="center" style="margin-top: 20px">
        <h4> Enter your Favorite for the Week!</h4>
        <form method="post" action="">
          <div class="form-group">
            <label for="sel1">Select Topic from Dropdown List</label>
            <select class="form-control" id="sel1" name="topic">
              <?php foreach ($opts as $opt) : ?>
                  <option value=<?php echo $opt['topic']?>>
                    <?php 
                        echo htmlspecialchars($opt['topic']);
                    ?>
                  </option>
              <?php endforeach; ?>                    
            </select>
            <!--input type="text" name="topic" class="form-control" placeholder="Topic for the Week" value="<?php echo $_POST['topic']; ?>" -->
          </div><!--end form-group-->
          <div class="form-group">
            <input type="text" name="favorite" class="form-control" placeholder="Your Favorite" value="<?php echo $_POST['favorite']; ?>" />
          </div><!--end form-group-->
          <div class="form-group">
            <textarea class="form-control" id="message" name="comment" placeholder="Comment"><?php echo $_POST['comment']; ?></textarea>
          </div><!--end form-group-->
          <div class="form-group">
            <input type="submit" name="submit" class="btn btn-success btn-lg" value="Submit Favorite" />
          </div><!--end form-group-->
        </form>
      </div><!--end emailForm-->
    </div><!--end row-->
  </div><!--end contactusform-->

  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <h2 class="text-center text-capitalize">Sample Favorites from Family</h2>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <div class="row">
        <?php
          $columns = ["username", "topic", "favorite", "comments"];
          $column = $columns[rand(0,3)];
          //print_r($column); echo "<br/>";

          switch ($column) {
              case "username":
                  $stmt = $pdo->query('SELECT * FROM favorites ORDER BY username');
                break;
              case "topic":
                  $stmt = $pdo->query('SELECT * FROM favorites ORDER BY topic');
                break;
              case "favorite":
                  $stmt = $pdo->query('SELECT * FROM favorites ORDER BY favorite');
                break;
              case "comments":
                  $stmt = $pdo->query('SELECT * FROM favorites ORDER BY comments');
              break;          
              default:
              $stmt = $pdo->query('SELECT * FROM favorites ORDER');
          }
          $row_count = $stmt->rowCount();
          //print_r($row_count);
          $favs = $stmt->fetchAll();
          //print_r($favs);

          //Check number of favorites in database
          if($row_count <= 2) {$y = $row_count-1;} else {$y = 2;} 
            for ($x=0; $x <= $y; $x++) {
                $row = $favs[$x]; 
        ?> <!--Display Favorites-->
                <div class="col-sm-4">
                    <table class="table table-bordered" style="border-style: solid; border-width: 5px; background-color: Cornsilk;">
                        <thead>
                        <tr>
                            <th style="width:30%">Name</th>
                            <th style="width:70%">Content</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>User Name</td>
                            <td><?php echo $row['username'] ?></td>
                        </tr>
                        <tr>
                            <td>Topic</td>
                            <td><?php echo $row['topic'] ?></td>
                        </tr>
                        <tr>
                            <td>Favorite</td>
                            <td><?php echo $row['favorite'] ?></td>        
                        </tr>
                        <tr>
                            <td>Comments</td>
                            <td><?php echo $row['comments'] ?></td>        
                        </tr>
                        </tbody>
                    </table>
                </div><!--end col-md-2-->                
        <?php }?> <!--end Display Favorites-->
    </div><!--- /.row--> 
</div><!--- /.container-->

  <div class="exclusive-group" align="center">
    <p>
    <?php
        if ($_SESSION["loggedin"]) {
          echo "You are Logged In as: " . $_SESSION["username"];
        }
        else {
          echo "Your are Not Logged In.";
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
