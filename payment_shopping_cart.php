<?php

//payment.php

    include('database_connection_shopping_cart.php');

    //echo '<p>Database Loaded without Errors</p>';

    if(isset($_POST["token"])) {
        require_once './vendor/autoload.php';

        \Stripe\Stripe::setApiKey('');

        $customer = \Stripe\Customer::create(array(
            'email'   => $_POST["email_address"],
            'source'  => $_POST["token"],
            'name'   => $_POST["customer_name"],
            'address'  => array(
             'line1'   => $_POST["customer_address"],
             'postal_code' => $_POST["customer_pin"],
             'city'   => $_POST["customer_city"],
             'state'   => $_POST["customer_state"],
             'country'  => 'US'
            )
           ));
        //echo 'customer: '; print_r($customer); echo '<br/><br/>';

        $order_number = rand(100000,999999);

        $charge = \Stripe\Charge::create(array(
            'customer'  => $customer->id,
            'amount'  => $_POST["total_amount"] * 100,
            'currency'  => $_POST["currency_code"],
            'description' => $_POST["item_details"],
            'metadata'  => array(
             'order_id'  => $order_number
            )
           ));
           //echo 'charge: '; print_r($charge); echo '<br/><br/>';

        $response = $charge->jsonSerialize();
        //echo 'response: '; print_r($response); echo '<br/><br/>';

        if($response["amount_refunded"] == 0 && empty($response["failure_code"]) && $response['paid'] == 1 && $response["captured"] == 1 && $response['status'] == 'succeeded') {
           
            $amount = $response["amount"]/100;
            
            $order_data = array(
                ':order_number'   => $order_number,
                ':order_total_amount' => $amount,
                ':transaction_id'  => $response["balance_transaction"],
                ':card_cvc'    => $_POST["card_cvc"],
                ':card_expiry_month' => $_POST["card_expiry_month"],
                ':card_expiry_year'  => $_POST["card_expiry_year"],
                ':order_status'   => $response["status"],
                ':card_holder_number' => $_POST["card_holder_number"],
                ':email_address'  => $_POST['email_address'],
                ':customer_name'  => $_POST["customer_name"],
                ':customer_address' => $_POST['customer_address'],
                ':customer_city'  => $_POST['customer_city'],
                ':customer_pin'   => $_POST['customer_pin'],
                ':customer_state'  => $_POST['customer_state'],
                ':customer_country'  => $_POST['customer_country']
            );
            //echo 'order data: '; print_r($order_data); echo '<br/><br/>';  
        
            $query = 'INSERT INTO order_table(order_number, order_total_amount, transaction_id, card_cvc, card_expiry_month, card_expiry_year, order_status, card_holder_number, email_address, customer_name, customer_address, customer_city, customer_pin, customer_state, customer_country) VALUES(:order_number, :order_total_amount, :transaction_id, :card_cvc, :card_expiry_month, :card_expiry_year, :order_status, :card_holder_number, :email_address, :customer_name, :customer_address, :customer_city, :customer_pin, :customer_state, :customer_country)
            ';
        
            $statement = $pdo->prepare($query);
            //echo 'statement: '; print_r($statement); echo '<br/><br/>';
        
            $statement->execute($order_data);
        
            $order_id = $pdo->lastInsertId();
            //echo 'order id: '; print_r($order_id); echo '<br/><br/>';

            //echo '<p>php Order Saved in Order_Table</p>';
            //echo '$_SESSION ["shopping_cart"]: '; print_r($_SESSION["shopping_cart"]); echo '<br/><br/>';            

            foreach($_SESSION["shopping_cart"] as $keys => $values) {

                $order_item_data = array('order_id'  => $order_id, 'order_item_name' => $values["product_name"], 'order_item_quantity' => $values["product_quantity"], 'order_item_price' => $values["product_price"]); 
                //echo 'order item: '; print_r($order_item_data); echo '<br/><br/>';

                $query = 'INSERT INTO order_item(order_id, order_item_name, order_item_quantity, order_item_price) VALUES(:order_id, :order_item_name, :order_item_quantity, :order_item_price)';

                $statement = $pdo->prepare($query);
                //echo 'statement: '; print_r($statement); echo '<br/><br/>';

                $statement->execute($order_item_data);
            }
            //echo '<p>php Order Saved in Item_Table</p>';

            unset($_SESSION["shopping_cart"]);

            $_SESSION["success_message"] = "Payment is completed successfully. The TXN ID is " . $response["balance_transaction"] . "";

            header('location: products.php');
        }
    }

?>