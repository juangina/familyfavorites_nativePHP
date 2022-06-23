<?php
    //order_process.php
    require 'startup.php';

    $total_price = 0;

    $item_details = '';

    $order_details = '
        <div class="table-responsive" id="order_table">
        <table class="table table-bordered table-striped">
        <tr>  
                    <th>Product Name</th>  
                    <th>Quantity</th>  
                    <th>Price</th>  
                    <th>Total</th>  
                </tr>
        ';

    if(!empty($_SESSION["shopping_cart"])) {
        foreach($_SESSION["shopping_cart"] as $keys => $values) {
            $order_details .= '
                <tr>
                <td>'.$values["product_name"].'</td>
                <td>'.$values["product_quantity"].'</td>
                <td align="right">$ '.$values["product_price"].'</td>
                <td align="right">$ '.number_format($values["product_quantity"] * $values["product_price"], 2).'</td>
                </tr>
                ';
            $total_price = $total_price + ($values["product_quantity"] * $values["product_price"]);

            $item_details .= $values["product_name"] . ', ';
        }

        $item_details = substr($item_details, 0, -2);
        $order_details .= '
            <tr>  
                    <td colspan="3" align="right">Total</td>  
                    <td align="right">$ '.number_format($total_price, 2).'</td>
                </tr>
            ';
    }
    
    $order_details .= '</table>';
?>

<!DOCTYPE html>
<html>

<head>
    <title>PHP Shopping Cart with Stripe Payment Integration</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="css/styles.css" />
    <link rel="stylesheet" type="text/css" href="css/navbar.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://js.stripe.com/v2/"></script>
    <script src="js/jquery.creditCardValidator.js"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Great+Vibes|Lato|Raleway" rel="stylesheet">

    <!--local styling--> 
    <style>
        .popover
        {
            width: 100%;
            max-width: 800px;
        }
        /*
        .row, {
            background-color: rgb(236, 93, 23);
            border: rgb(255, 199, 64) solid 5px;
        }
        .topbanner, .container, .container-fluid {
            background-color: rgb(236, 93, 23);
            border: rgb(255, 199, 164) solid 5px;
        }
        */
        .require
        {
            border:1px solid #FF0000;
            background-color: #cbd9ed;
        }
    </style>
    <!--local styling-->
     
</head>

<body>

<!--container one-->
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
                echo '<li id="admin" align="center"><a href="/products.php">Favorite Products</a></li>';
                echo '<li id="admin" align="center"><a href="/cart.php">Shopping Cart</a></li>';
                echo '<li id="admin" align="center"><a href="/contact.php">Contact</a></li>';
                echo '<li id="admin" align="center"><a href="/debug_out.php">Debug Out</a></li>';
                echo '<li id="admin" align="center"><a href="/logout.php">Logout</a></li>';
            } else {
                echo '<li align="center"><a href="/logout.php">Logout</a></li>';
                echo '<li align="center"><a href="/dashboard.php">Dashboard</a></li>';
                echo '<li align="center"> <a href="/products.php">Favorite Products</a></li>';
                echo '<li align="center"> <a href="/cart.php">Shopping Cart</a></li>';
                echo '<li align="center"> <a href="/contact.php">Contact Me</a></li>';
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
    <div class="container">
        <br />
        <h3 align="center"><a href="#">Purchasing Form</a></h3>
        <br />
        <span id="message"></span>
        <div class="panel panel-default">
            <div class="panel-heading">Order Process</div>
                <div class="panel-body">
                    <form method="post" id="order_process_form" action="payment_shopping_cart.php">
                        <div class="row">
                            <div class="col-md-8" style="border-right:1px solid #ddd;">
                                <h4 align="center">Customer Details</h4>
                                <div class="form-group">
                                    <label><b>Card Holder Name <span class="text-danger">*</span></b></label>
                                    <input type="text" name="customer_name" id="customer_name" class="form-control" value="" />
                                    <span id="error_customer_name" class="text-danger"></span>
                                </div>
                                <div class="form-group">
                                    <label><b>Email Address <span class="text-danger">*</span></b></label>
                                    <input type="text" name="email_address" id="email_address" class="form-control" value="" />
                                    <span id="error_email_address" class="text-danger"></span>
                                </div>
                                <div class="form-group">
                                    <label><b>Address <span class="text-danger">*</span></b></label>
                                    <textarea name="customer_address" id="customer_address" class="form-control"></textarea>
                                    <span id="error_customer_address" class="text-danger"></span>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><b>City <span class="text-danger">*</span></b></label>
                                            <input type="text" name="customer_city" id="customer_city" class="form-control" value="" />
                                            <span id="error_customer_city" class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><b>Zip <span class="text-danger">*</span></b></label>
                                            <input type="text" name="customer_pin" id="customer_pin" class="form-control" value="" />
                                            <span id="error_customer_pin" class="text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><b>State </b></label>
                                            <input type="text" name="customer_state" id="customer_state" class="form-control" value="" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><b>Country <span class="text-danger">*</span></b></label>
                                            <input type="text" name="customer_country" id="customer_country" class="form-control" />
                                            <span id="error_customer_country" class="text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <hr />
                                <h4 align="center">Payment Details</h4>
                                <div class="form-group">
                                    <label>Card Number <span class="text-danger">*</span></label>
                                    <input type="text" name="card_holder_number" id="card_holder_number" class="form-control" placeholder="1234 5678 9012 3456" maxlength="20" onkeypress="" />
                                    <span id="error_card_number" class="text-danger"></span>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Expiry Month</label>
                                            <input type="text" name="card_expiry_month" id="card_expiry_month" class="form-control" placeholder="MM" maxlength="2" onkeypress="return only_number(event);" />
                                            <span id="error_card_expiry_month" class="text-danger"></span>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Expiry Year</label>
                                            <input type="text" name="card_expiry_year" id="card_expiry_year" class="form-control" placeholder="YYYY" maxlength="4" onkeypress="return only_number(event);" />
                                            <span id="error_card_expiry_year" class="text-danger"></span>
                                        </div>
                                        <div class="col-md-4">
                                            <label>CVC</label>
                                            <input type="text" name="card_cvc" id="card_cvc" class="form-control" placeholder="123" maxlength="4" onkeypress="return only_number(event);" />
                                            <span id="error_card_cvc" class="text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <br />
                                <div align="center">
                                    <input type="hidden" name="total_amount" value="<?php echo $total_price; ?>" />
                                    <input type="hidden" name="currency_code" value="USD" />
                                    <input type="hidden" name="item_details" value="<?php echo $item_details; ?>" />
                                    <input type="button" name="button_action" id="button_action" class="btn btn-success btn-sm" onclick="stripePay(event)" value="Pay Now" />
                                </div>
                                <br />
                            </div>
                            <div class="col-md-4">
                                <h4 align="center">Order Details</h4>
                                <?php
                                    echo $order_details;
                                ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<!--end container two-->

</body>
</html>

<script>

    function validate_form() {
        console.log('Validating Form');
        //form values    
            var valid_card = 0;
            var valid = true;
            var card_cvc = $('#card_cvc').val();
            var card_expiry_month = $('#card_expiry_month').val();
            var card_expiry_year = $('#card_expiry_year').val();
            var card_holder_number = $('#card_holder_number').val();
            var email_address = $('#email_address').val();
            var customer_name = $('#customer_name').val();
            var customer_address = $('#customer_address').val();
            var customer_city = $('#customer_city').val();
            var customer_pin = $('#customer_pin').val();
            var customer_country = $('#customer_country').val();
            var name_expression = /^[a-z ,.'-]+$/i;
            var email_expression = /^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/;
            var month_expression = /^01|02|03|04|05|06|07|08|09|10|11|12$/;
            var year_expression = /^2017|2018|2019|2020|2021|2022|2023|2024|2025|2026|2027|2028|2029|2030|2031$/;
            var cvv_expression = /^[0-9]{3,3}$/;
        //end form values

        $('#card_holder_number').validateCreditCard(
            function(result) {
                if(result.valid) {
                    $('#card_holder_number').removeClass('require');
                    $('#error_card_number').text('');
                    valid = true;
                }
                else {
                    $('#card_holder_number').addClass('require');
                    $('#error_card_number').text('Invalid Card Number');
                    valid = false;
                }
            }
        );

        if(!name_expression.test(customer_name)) {
            $('#customer_name').addClass('require');
            $('#error_customer_name').text('Invalid Name');
            valid = false;
        }
        else {
            $('#customer_name').removeClass('require');
            $('#error_customer_name').text('');
            valid = true;
        }

        if(!email_expression.test(email_address)) {
            $('#email_address').addClass('require');
            $('#error_email_address').text('Invalid Email Address');
            valid = false;
        }
        else {
            $('#email_address').removeClass('require');
            $('#error_email_address').text('');
            valid = true;
        }

        if(customer_address == '') {
            $('#customer_address').addClass('require');
            $('#error_customer_address').text('Enter Address Detail');
            valid = false;
        }
        else {
            $('#customer_address').removeClass('require');
            $('#error_customer_address').text('');
            valid = true;
        }

        if(customer_city == '') {
            $('#customer_city').addClass('require');
            $('#error_customer_city').text('Enter City');
            valid = false;
        }
        else {
            $('#customer_city').removeClass('require');
            $('#error_customer_city').text('');
            valid = true;
        }

        if(customer_pin == '') {
            $('#customer_pin').addClass('require');
            $('#error_customer_pin').text('Enter Zip code');
            valid = false;
        }
        else {
            $('#customer_pin').removeClass('require');
            $('#error_customer_pin').text('');
            valid = true;
        }

        if(customer_country == '') {
            $('#customer_country').addClass('require');
            $('#error_customer_country').text('Enter Country Detail');
            valid = false;
        }
        else {
            $('#customer_country').removeClass('require');
            $('#error_customer_country').text('');
            valid = true;
        }

        if(!month_expression.test(card_expiry_month)) {
            $('#card_expiry_month').addClass('require');
            $('#error_card_expiry_month').text('Invalid Data');
            valid = false;
        }
        else { 
            $('#card_expiry_month').removeClass('require');
            $('#error_card_expiry_month').text('');
            valid = true;
        }

        if(!year_expression.test(card_expiry_year)) {
            $('#card_expiry_year').addClass('require');
            $('#error_card_expiry_year').error('Invalid Data');
            valid = false;
        }
        else {
            $('#card_expiry_year').removeClass('require');
            $('#error_card_expiry_year').error('');
            valid = true;
        }

        if(!cvv_expression.test(card_cvc)) {
            $('#card_cvc').addClass('require');
            $('#error_card_cvc').text('Invalid Data');
            valid = false;
        }
        else {
            $('#card_cvc').removeClass('require');
            $('#error_card_cvc').text('');
            valid = true;
        }

        return valid;
    }
    Stripe.setPublishableKey('pk_test_51Hxdp0CVjbS5QhMx2rp0OHKNkrjeICdbxl39bKiXZkryJAFOjB8dNJG3k5vt9DzKADCCtYf1Vmf4At9WwNcU5oMG00mI933PkE');

    function stripeResponseHandler(status, response) {
        if(response.error) {
            $('#button_action').attr('disabled', false);
            $('#message').html(response.error.message).show();
        } 
        else {
            var token = response['id'];
            $('#order_process_form').append("<input type='hidden' name='token' value='" + token + "' />");
                $('#order_process_form').submit();
        }
    }

    function stripePay(event) {
        event.preventDefault();
 
        if(validate_form() == true) {
            $('#button_action').attr('disabled', 'disabled');
            $('#button_action').val('Payment Processing....');
            Stripe.createToken({
                number:$('#card_holder_number').val(),
                cvc:$('#card_cvc').val(),
                exp_month : $('#card_expiry_month').val(),
                exp_year : $('#card_expiry_year').val()
                }, stripeResponseHandler
            );
        return false;
        }
    }

    function only_number(event) {
        var charCode = (event.which) ? event.which : event.keyCode;
        if (charCode != 32 && charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }
 
</script>