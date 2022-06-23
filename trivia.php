<?php
require 'startup.php';
    // Check if the user is not logged in, redirect him to login page
        if($_SESSION["loggedin"] === NULL) {
            header("location: login.php");
        exit;
        }
    // end Check if the user is not logged in, redirect him to login page

    /* Database credentials */
        if ($local == True) {
            define('DB_SERVER', '');
            define('DB_USERNAME', '');
            define('DB_PASSWORD', '');
            define('DB_NAME', '');
        }
        else {
            $db = parse_url(getenv("DATABASE_URL"));
        }
    /* end Database credentials */

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
    /* end Attempt to connect to MySQL database */

    //Query the db for trivia question for the dropdown list
        $sql = "SELECT * FROM trivia_question";
        if($stmt = $pdo->prepare($sql)) {    
            if($stmt->execute()) {  
                if($triviaCount = $stmt->rowCount()) { 
                    while ($row = $stmt->fetch()) {
                        $opts[] = [
                            'question'=>$row['question'],
                            'ans1'=>$row['ans1'],
                            'ans2'=>$row['ans2'],
                            'ans3'=>$row['ans3'],
                            'ans4'=>$row['ans4'],
                            'hint'=>$row['hint'],
                            'answer'=>$row['answer']
                        ];
                    }
                    //print_r($opts);
                }else{$debug2 = "stmt->rowCount error";}
            }else{$debug2 = "stmt->execute error";}
        }else{$debug2 = "pdo->prepare error";}
        $randOpt = rand(0, $triviaCount-1);
    //end Query the db for favorite topics for the dropdown list

    //Random Trivia Question of the Week
        //$triviaQuestion = "Barack Obama's Favorite Movie?";
        $triviaQuestion = $opts[$randOpt]['question'];
        //$triviaAnswer = ['Scarface', 'The Godfather', 'Casino', 'Training Day'];
        $triviaAnswer = [$opts[$randOpt]['ans1'], $opts[$randOpt]['ans2'], $opts[$randOpt]['ans3'], $opts[$randOpt]['ans4']];
        //$triviaHint = 'One of the original gangster movies';
        $triviaHint = $opts[$randOpt]['hint'];
    //end Random Trivi Question of the week    

    //Automate the correct row
        switch ($opts[$randOpt]['answer']) {
            case 'ans1':
                $ans1Class = "correct-answer answer-one answer";
                $ans1TextClass = 'correct-answer-text answer-text';
                $ans2Class = "wrong-answer-two answer-two answer";
                $ans2TextClass = 'wrong-text-two answer-text';
                $ans3Class = "wrong-answer-three answer-three answer";
                $ans3TextClass = 'wrong-text-three answer-text';
                $ans4Class = "wrong-answer-four answer-four answer";
                $ans4TextClass = 'wrong-text-four answer-text';
                break;
            case 'ans2':
                $ans1Class = "wrong-answer-one answer-one answer";
                $ans1TextClass = 'wrong-text-one answer-text';
                $ans2Class = "correct-answer answer-two answer";
                $ans2TextClass = 'correct-answer-text answer-text';
                $ans3Class = "wrong-answer-three answer-three answer";
                $ans3TextClass = 'wrong-text-three answer-text';
                $ans4Class = "wrong-answer-four answer-four answer";
                $ans4TextClass = 'wrong-text-four answer-text';
                break;
            case 'ans3':
                $ans1Class = "wrong-answer-one answer-one answer";
                $ans1TextClass = 'wrong-text-one answer-text';
                $ans2Class = "wrong-answer-two answer-two answer";
                $ans2TextClass = 'wrong-text-two answer-text';
                $ans3Class = "correct-answer answer-three answer";
                $ans3TextClass = 'correct-answer-text answer-text';
                $ans4Class = "wrong-answer-four answer-four answer";
                $ans4TextClass = 'wrong-text-four answer-text';
                break;
            case 'ans4':
                $ans1Class = "wrong-answer-one answer-one answer";
                $ans1TextClass = 'wrong-text-one answer-text';
                $ans2Class = "wrong-answer-two answer-two answer";
                $ans2TextClass = 'wrong-text-two answer-text';
                $ans3Class = "wrong-answer-three answer-three answer";
                $ans3TextClass = 'wrong-text-three answer-text';
                $ans4Class = "correct-answer answer-four answer";
                $ans4TextClass = 'correct-answer-text answer-text';
                break;
            default:
            $ans1Class = "wrong-answer-one answer-one answer";
            $ans1TextClass = 'wrong-text-one answer-text';
            $ans2Class = "wrong-answer-two answer-two answer";
            $ans2TextClass = 'wrong-text-two answer-text';
            $ans3Class = "wrong-answer-three answer-three answer";
            $ans3TextClass = 'wrong-text-three answer-text';
            $ans4Class = "wrong-answer-four answer-four answer";
            $ans4TextClass = 'wrong-text-four answer-text';                                            
        }
    //end Automate the corrct row
?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Family Application to increase communication and bonding - Dashboard"/>

    <title>Family Favorites</title>

    <!-- CSS Stylesheets -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/styles.css" />
    <link rel="stylesheet" type="text/css" href="css/navbar.css" />
    <link rel="stylesheet" type="text/css" href="css/trivia.css" />    
    <link rel="stylesheet" type="text/css" href="css/get-a-quote-form.css" />

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Great+Vibes|Lato|Raleway" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" href="images/coffee-website-favicon.jpg">

    <!--local test styling> 
        <style>
        .container, .container-fluid {
            background-color: rgb(109, 240, 194);
            border: black solid 5px;
            color: rgb(0, 0, 0);
        }
        
        .row {
            background-color: rgb(236, 93, 23);
            border: rgb(255, 199, 64) solid 5px;
        }
        
        [class*="col"]{
            border: black solid 5px;
            background-color: rgb(100, 0, 228);
            color: rgb(255, 255, 255);   
        }
        </style>
    <end local styling-->
</head>

<body>

<?php 
    //Testing Console_Log
    //console_log("Dashboard Page Loading");
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
                echo '<li id="admin" align="center"> <a href="/trivia.php">Trivia Game</a></li>';                
                echo '<li id="admin" align="center"> <a href="/products.php">Products</a></li>';
                echo '<li id="admin" align="center"><a href="/contact.php">Contact</a></li>';
                echo '<li id="admin" align="center"><a href="/test_pages/debug_out.php">Debug Out</a></li>';
                echo '<li id="admin" align="center"><a href="/logout.php">Logout</a></li>';
              } else {                
                echo '<li align="center"><a href="/dashboard.php">Dashboard</a></li>';
                echo '<li align="center"> <a href="/contact.php">Contact Me</a></li>';
                echo '<li align="center"> <a href="/logout.php">Logout</a></li>';
              }
            ?>
          </ul>
        </div><!--end navbar-collapse-->
      </div><!--end container-->
    </nav><!--end nav-->
    <h1 align="center">Johnson-Thomas Estate<br /><span class="cursive">Favorite Things Project</span></h1>
  </div><!--end topbanner-->

<!--Triva Game container -->
    <div class='card-container'>          
        <div class = 'card'>       

            <div class = 'reset-box'>
                <div class = 'reset-hint-text' id='_reset'>
                    RESET
                </div>
            </div>    
        
            <div class = "question">
                <div class = 'question-text'>
                    <?php echo $triviaQuestion; ?>
                </div>
            </div>
            
            <div class = 'hint-box'>
                <div class = 'hide-hint-text'>
                    SHOW/HIDE HINT
                </div>
            </div>
            
            <div class = 'hint' id='_hint'> 
                <div class = 'hide-hint-text'>
                    <?php echo $triviaHint; ?>
                </div>
            </div>
            
            <!--div class = 'wrong-answer-one answer-one answer'-->
            <div class = '<?php echo $ans1Class; ?>' >
                <!--div class= 'wrong-text-one answer-text'-->
                <div class = <?php echo $ans1TextClass; ?> >
                    <?php echo $triviaAnswer[0]; ?>
                </div>
            </div>

            <!--div class = 'wrong-answer-two answer-two answer'-->
            <div class = '<?php echo $ans2Class; ?>' >
                <!--div class= 'wrong-text-two answer-text'-->
                <div class = <?php echo $ans2TextClass; ?> >    
                    <?php echo $triviaAnswer[1]; ?>
                </div>
            </div>
            
            <!--div class = 'wrong-answer-three answer-three answer'-->
            <div class = '<?php echo $ans3Class; ?>' >
                <!--div class= 'wrong-text-three answer-text'-->
                <div class = <?php echo $ans3TextClass; ?> >    
                    <?php echo $triviaAnswer[2]; ?>
                </div>
            </div>
            
            <!--div class = 'wrong-answer-four answer-four answer'-->
            <div class = '<?php echo $ans4Class; ?>' >
                <!--div class = 'wrong-text-four answer-text'-->
                <div class = <?php echo $ans4TextClass; ?> >    
                    <?php echo $triviaAnswer[3]; ?>
                </div>
            </div>

            <div class = 'result'>
                <span class = 'emoticon' style='color: white'>emoticon</span>
                <div class = 'smiley'> <img src='https://content.codecademy.com/courses/jquery/effects/smile_icon.svg' /> </div>
                <div class = 'frown'> <img src='https://content.codecademy.com/courses/jquery/effects/frown_icon.svg' />  </div>
            </div>
        </div>
    </div>
<!--end Triva Game container-->    

  <div class="exclusive-group" id="login-stat-sect" align="center">
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
    <!--script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script-->
    <script
    src="https://code.jquery.com/jquery-3.2.1.min.js"
    integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
    crossorigin="anonymous"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  <!-- end jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src='js/trivia.js'></script>

</body>
</html>
