<?php
//Not sure what this is for?
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;
  // Include PHPMailer library files
  require 'PHPMailer/Exception.php';
  require 'PHPMailer/PHPMailer.php';
  require 'PHPMailer/SMTP.php';
  require 'startup.php';
  $ref = $_SERVER['SCRIPT_NAME'];
  
  if ($_POST["submit"]) {

    if (!$_POST['name']) {
      $error="<br />Please enter your name";
    }

    if (!$_POST['email']) {
      $error.="<br />Please enter your email address";
    }

    if (!$_POST['message']) {
      $error.="<br />Please enter a message";
    }

    if ($_POST['email']!="" AND !filter_var($_POST['email'],
      FILTER_VALIDATE_EMAIL)) {
      $error.="<br />Please enter a valid email address";
    }

    if ($error) {
      $result='<div class="alert alert-danger"><strong>There were error(s)
      in your form:</strong>'.$error.'</div>';
    } 
    else {
      //Collect data from form for processing
      $mail_name = $_POST['name'];
      $mail_email = $_POST['email'];
      $mail_message = $_POST['message'];

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
      $mssg .= "Defining Variables.";

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
        $mssg .= "New Login Object Created.";
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $mssg .= "Setting Login Default Attributes.";
        // Set the PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $mssg .= "Connection to Database Made.";
        $sql = "SELECT * FROM administrator WHERE username = 'juaneric'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch();

        /* THE EMAIL WHERE YOU WANT TO RECIEVE THE CONTACT MESSAGES */
        //Create mail object
        $mail = new PHPMailer;          
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host     = 'in-v3.mailjet.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'f6fd294a103a7c7c3a78ef8944c0b93a';       
        $mail->Password = '8a474f26e541b5d97c795b13f8fbbbb1';
        //$_SESSION["debug"] = $mail->Password;
        $mail->SMTPSecure = 'tls';
        $mail->Port     = 587;

        //Attempt to send message
        $mail->setFrom('jejlifestyle@theaccidentallifestyle.net', 'Family Favorites App');
        $mail->addReplyTo('jejlifestyle@theaccidentallifestyle.net', 'Family Favorites App');
        // Add a recipient
        $mail->addAddress("ericrenee21@gmail.com");
        //$_SESSION["debug"] = $mail_email;
        // Add cc or bcc 
          //$mail->addCC('cc@example.com');
          //$mail->addBCC('bcc@example.com');
        // Set Email subject
        $mail->Subject = 'Family Favorite Query';
        // Set email format to HTML
        $mail->isHTML(true);
        // Set Email body content
        $mail->Body = $mail_message . "\n" . $mail_email;
        //$_SESSION["debug"] = $mail;
        // Send email
        if(!$mail->send()){
          //Append Debug Message
          $mssg .='>Mail Confirmation Unsuccessful, Sorry!>';
          //$_SESSION["debug1"] = $mail;
          //$_SESSION["debug2"] = (extension_loaded('openssl')?'SSL loaded':'SSL not loaded')."\n";
        }else {
          //Append Debug Message
          $mssg .='Mail Confirmation Sent';
          //$_SESSION["debug1"] = $mail;
          //$_SESSION["debug2"] = (extension_loaded('openssl')?'SSL loaded':'SSL not loaded')."\n";
        }
        header("location: $ref");
      } 
      catch(PDOException $e) {
        die("ERROR: Could not connect. " . $e->getMessage());
        $mssg .= "Error, could not connect.";
      }
      $_SESSION["debug3"] = $mssg; 
    }
    $_SESSION["debug3"] = $mssg;
  }
?>

<!DOCTYPE html>
  <html lang="en">
    <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="description" content="">
      <!-- Meta Description -->
    <title>Contact Me - Family Favorites</title>
      <!-- CSS Stylesheets -->
    <link href="css/bootstrap.min.css" rel="stylesheet">  
    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <link rel="stylesheet" type="text/css" href="css/navbar.css" />
    <link rel="stylesheet" type="text/css" href="css/get-a-quote-form.css" />
    <!--link rel="stylesheet" type="text/css" href="css/contact-page-styling.css" /--> 
    <!-- Google Fonts -->
   <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
   <link href="https://fonts.googleapis.com/css?family=Great+Vibes|Lato|Raleway" rel="stylesheet">
    <!-- Favicon -->
   <link rel="icon" href="images/coffee-website-favicon.jpg">
  </head>
<body>
  <?php 
      //Testing Console_Log
      console_log("Contact Page Loading");
      console_log($_SESSION); 
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
                  echo '<li align="center"> <a href="/login.php">Login</a></li>';
                  echo '<li align="center"> <a href="/dashboard.php">Dashboard</a></li>';
                  echo '<li id="admin" align="center"><a href="/logout.php">Logout</a></li>';
                }
              ?>
          </ul>
        </div><!--end navbar-collapse-->
      </div><!--end container-->
    </nav><!--end nav-->
    <h1 align="center">Johnson-Thomas Estate<br /><span class="cursive">Favorite Things Project</span></h1>
  </div><!--end contact-banner-->

  <div class="container">
    <h2>Contact Me</h2>		
    <p>If you have a question or concern about how to use the Favorites application, please let me know.  I will monitor my email and will return your answer in 1-3 days.</p>
    <form method="post">
      <div class="form-group">
                  <input type="text" name="name" class="form-control" placeholder="Your Name" value="<?php echo $_POST['name']; ?>" />
      </div><!--end form-group-->
      <div class="form-group">
                  <input type="email" name="email" class="form-control" placeholder="Your Email" value="<?php echo $_POST['email']; ?>" />
      </div><!--end form-group-->
      <div class="form-group">
                  <input type="tel" name="phone" class="form-control" placeholder="Your Phone" value="<?php echo $_POST['phone']; ?>" />
      </div><!--end form-group-->
      <div class="form-group">
                  <textarea class="form-control" id="message" name="message" placeholder="Message"><?php echo $_POST['message']; ?></textarea>
      </div><!--end form-group-->
      <div class="form-group">
        <p>
          <input type="submit" name="submit" class="btn btn-success btn-lg" value="Send Message" />
          <input type="reset" name="reset" class="btn btn-default btn-lg" value="Reset">
        </p>
      </div><!--end form-group-->
    </form>
  </div><!--end contactusform-->

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
