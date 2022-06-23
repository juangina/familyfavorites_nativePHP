<?php
//database_connection.php
require 'startup.php';

//Database Variable Setup    
    if ($local == True) {
      define('DB_SERVER', '');
      define('DB_USERNAME', '');
      define('DB_PASSWORD', '');
      define('DB_NAME', '');
    }
    else {
    $db = parse_url(getenv("DATABASE_URL"));
    }
//End Database Variable Setup 

//Attempt to connect to MySQL database
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
    console_log($mssg);
  }
  catch(PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
    $mssg .= "Error, could not connect.";
    console_log($mssg);
  } 
//end Attempt to connect to MySQL database
?>




