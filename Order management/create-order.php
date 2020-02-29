<?php namespace Orders; ?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Styles -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <title>Create an order</title>
    </head>
    <body>
     <?php  
        include_once('templates/header.php');
        require 'db/db.php';
        require 'includes/process-order.php';

        $customer = new Customers();
        $customers = $customer->fetchValues();
        $product = new Products();
        $products = $product->fetchValues();
       
        $buyer = $totalamount  = $totaldiscount = $paidamount = $id = $productname = $unitprice = $prize ='';
        $quantity = $total = $discount = '';

        //Check if form was submited and collect input values
        if(isset($_POST['submit'])){
            
            //Order information
            $prize = $_POST['prize'];
            $buyer = trim($_POST['customer']);
            $totalamount =  trim($_POST['totalamount']);
            $totaldiscount = trim($_POST['totaldiscount']);
            $paidamount = trim($_POST['paidamount']);

            //Order item information
            $id = $_POST['id'];
            $productname = $_POST['productname'];
            $unitprice = $_POST['unitprice'];
            $quantity = $_POST['quantity'];
            $total = $_POST['total'];
            $discount = $_POST['discount'];

            
            $success = [];

            //Check if user has chosen a customer and products to order
            $errors = validateForm($buyer, $totalamount, $totaldiscount, $paidamount); 

            //If user has chosen a customer and selcted products to order
             if(empty($errors)){

                unset($_POST['submit']);
                $cust = new Customers();
                $cust = $cust->fetchValues($_POST['customer'], 'id');
                $cust = $cust[0];
                
                $computedOrder = computeOrder($_POST, $cust);
                $orderInfo = array_merge($computedOrder['order'], ['customer' => $_POST['customer']]);
                $orderItems = $computedOrder['items'];
                

                //Create a new order and save it to the database
                $newOrder = new Orders();
                $newOrder->setAttributes($orderInfo);
                $lastOrder = $newOrder->save();
                $orderId = $lastOrder['lastId'];
                
                //Create a success notification
                $success['order'] = '<div class="alert alert-success alertSuccess alert-dismissible fade show" role="alert">
                New order created. <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
             </button>
            </div>';

                //Check if order was successfully saved in the database and save order details
                if($lastOrder != false){
                    
                    foreach($orderItems as $orderitem){
                        $orderitem = array_merge($orderitem, ['orderid' => $orderId]);
                        $newOrderDetail = new OrderDetails();
                        $newOrderDetail->setAttributes($orderitem);
                        $newOrderDetail->save();    
                    }
                    //Print success notification
                    echo $success['order'];
                }
                //Restore default values to variables
                $buyer = $totalamount  = $totaldiscount = $paidamount = $id = $productname = $unitprice = $prize ='';
                $quantity = $total = $discount = '';
             }

        }
    ?>
     <div class="container">  
        <div clss="row"><h1 class="col-md-6" style="margin: 2rem 0;">Create an order</h1></div>    
        <?php 
            include_once('templates/order-form.php');
        ?>
    </div>
  
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script type="text/javascript" src="js/compute-order.js"></script>
  
    </body>

</html>