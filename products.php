<?php
require 'startup.php';

// Check if the user is not logged in, redirect him to login page
  if($_SESSION["loggedin"] === NULL) {
    header("location: login.php");
    exit;
  }
// end check  
?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Family Application to increase communication and bonding - Home Page"/>

    <title>Family Favorites - Products</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <!--script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script-->
    <!--script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script-->


    <!-- CSS Stylesheets -->
    <link rel="stylesheet" href="css/bootstrap.min.css" >
    <link rel="stylesheet" type="text/css" href="css/styles.css" />
    <link rel="stylesheet" type="text/css" href="css/navbar.css" />
    <link rel="stylesheet" type="text/css" href="css/get-a-quote-form.css" />
    <!--link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" /-->

    <script src="js/bootstrap.min.js"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Great+Vibes|Lato|Raleway" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" href="images/coffee-website-favicon.jpg">

    <!--local styling--> 
    <style>
        .popover
        {
            width: 100%;
            max-width: 800px;
        }
    </style>
    <!--end local styling-->
</head>

<body>

<?php 
  //Testing Console_Log
  //console_log("Index Page Loading");
  //console_log($_SESSION); 
?>
<!--container one-->
  <div class="topbanner" id="nav-sect">
    <nav class="navbar navbar-default navbar-static navbar-transparent" role="navigation">
      <div class="container">

        <div class="navbar-header">
          <a class="navbar-brand" href="/index.php">
            <img class="logo" alt="Brand" src="images/coffee-shop-logo.png">
          </a>
          <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=" .navbar-collapse">
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
                echo '<li id="admin" align="center"><a href="/dashboard.php">Dashboard</a></li>';
                echo '<li align="center"> <a href="/contact.php">Contact Me</a></li>';
                echo '<li id="admin" align="center"><a href="/logout.php">Logout</a></li>';
              }
            ?>
          </ul>
        </div><!--end navbar-collapse-->
      </div><!--end container-->
    </nav><!--end nav-->
    <h1 align="center">Johnson-Thomas Estate<br /><span class="cursive">Favorite Things Project</span></h1>
  </div><!--end topbanner-->
<!--end container one-->

<!--container two-->      
    <div class="container" id="nav-sect">
        <h3 align="center"><a href="#">Favorite Products</a></h3>

    <!--This nav is used to display the cart navagation options-->
        <nav class="navbar navbar-default " role="navigation">
            <div class="container-fluid">
                <!--Horizonal nav bar header Div-->
                <div class="navbar-header">
                    <!--This button operates a collapsed link List for small devices-->
                    <button type="button" class="navbar-toggle" data-toggle="collapse"data-target="#navbar-cart">
                        <span class="sr-only">Menu</span>
                        <span class="glyphicon glyphicon-menu-hamburger"></span>
                    </button>
                    <a class="navbar-brand" href="/cart.php">Shopping Cart</a>
                </div>
                <!--Horizonal nav bar link list Div-->
                <div class="collapse navbar-collapse " id="navbar-cart" >
                    <ul class="nav navbar-nav">
                        <li><!--This button link operates a popup Table-->
                            <!--This button targets a jQuery .popover method-->
                            <a class="btn" data-placement="bottom" title="Shopping Cart" id="cart-popover" >
                                <span class="glyphicon glyphicon-shopping-cart"></span>
                                <span class="badge"></span>
                                <span class="total_price">$ 0.00</span>
                            </a>  
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    <!--This nav is used to display the cart navagation options-->
    
    <!--This div is referenced by the jQuery function to display the popover content-->
        <div id="popover_content_wrapper" style="display: none">
            <span id="cart_details"></span>
            <div align="right">
                <a class="btn btn-primary" href="order_process_shopping_cart.php" id="check_out_cart">
                    <span class="glyphicon glyphicon-shopping-cart"></span> Check out
                </a>
                <a class="btn btn-default" href="#" id="clear_cart">
                    <span class="glyphicon glyphicon-trash"></span> Clear
                </a>
            </div>
        </div>
    <!--end This div is referenced by the jQuery load cart function to display the popover content-->

    <!--This div is used to display a Success Alert after completing a purchase-->
        <?php
            if(isset($_SESSION['success_message'])) {
                echo '<div class="alert alert-success">' . $_SESSION["success_message"] . '</div>';
                unset($_SESSION["success_message"]);
            }
        ?>
    <!--end This is used to display a Success Alert after completing a purchase-->

    <!--This div is referenced by the jQuery load item function to display the products-->
        <div class="row" id="display_item">
            <!--Product Items will be displayed here-->
        </div><br /><br />
    <!--end This div is referenced by the jQuery load item function to display the products-->

    </div>
<!--end container two-->

<!--container three-->
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
    </div>
<!--end container three-->

</body>
</html>

<script>  
    $(document).ready(function(){
            //alert('DOM fully loaded!');
            load_product();
            load_cart_data();

            function load_product() {
                $.ajax({
                    url:"fetch_item_shopping_cart.php",
                    method:"POST",
                    success:function(data) {
                    $('#display_item').html(data);
                    }
                })
            }

            function load_cart_data() {
                $.ajax({
                    url:"fetch_cart_shopping_cart.php",
                    method:"POST",
                    dataType:"json",
                    success:function(data) {
                    $('#cart_details').html(data.cart_details);
                    $('.total_price').text(data.total_price);
                    $('.badge').text(data.total_item);
                    }
                })
            }
            //This function is called when the cart button is pressed
            $('#cart-popover').popover({
                html : true,
                container : 'body',
                content:function() {
                    return $('#popover_content_wrapper').html();
                }
            });

            $(document).on('click', '.add_to_cart', function() {
                var product_id = $(this).attr('id');
                var product_name = $('#name'+product_id+'').val();
                var product_price = $('#price'+product_id+'').val();
                var product_quantity = $('#quantity'+product_id).val();
                var action = 'add';
                if(product_quantity > 0) {
                    $.ajax({
                        url:"action_shopping_cart.php",
                        method:"POST",
                        data:{product_id:product_id, product_name:product_name, product_price:product_price, product_quantity:product_quantity, action:action},
                        success:function(data) {
                            load_cart_data();
                            alert("Item has been Added into Cart");
                        }
                    })
                }
                else {
                    alert("Please Enter Number of Quantity");
                }
            });

            $(document).on('click', '.delete', function() {
                var product_id = $(this).attr('id');
                var action = 'remove';
                if(confirm("Are you sure you want to remove this product?")) {
                    $.ajax({
                        url:"action_shopping_cart.php",
                        method:"POST",
                        data:{product_id:product_id, action:action},
                        success:function(data) {
                            load_cart_data();
                            $('#cart-popover').popover('hide');
                            alert("Item has been removed from Cart");
                        }
                    })
                }
                else
                {
                    return false;
                }
            });

            $(document).on('click', '#clear_cart', function() {
                var action = 'empty';
                $.ajax({
                    url:"action_shopping_cart.php",
                    method:"POST",
                    data:{action:action},
                    success:function() {
                        load_cart_data();
                        $('#cart-popover').popover('hide');
                        alert("Your Cart has been clear");
                    }
                })
            });
        }        
    );
</script>

