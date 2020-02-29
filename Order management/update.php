<?php
namespace Orders;

if(!isset($_GET['id'])) {
    header('Location: orders.php');exit;
}

require 'db/db.php';
require 'includes/process-order.php';
$order = new Orders();
$order = $order->fetchValues($_GET['id'], 'id');
if( $order == FALSE) { //if not entry was found
    header('Location: orders.php');exit;
}

//Set existing order values in the form
$order = $order[0];
$orderId = $order['id'];
$buyer = $order['customer'];
$totalamount = intval($order['totalamount']);
$totaldiscount = intval($order['totaldiscount']);
$paidamount = intval($order['paidamount']);

//Find all customers
$customer = new Customers();
$customers = $customer->fetchValues();
$type = $customer->fetchValues($buyer, 'id');
$type = $type[0];

//List all products
$product = new Products();
$products = $product->fetchValues();

//Calculate discount percentage for each product
foreach ($products as $art) { 
    $disc = calculateDiscount($type['type'], $art['productname'] );         
    $prodid = ($art['id'] - 1);
    $discountpercentage[$prodid]  = $disc;
}

//List all order articles
$orderArticle = new OrderDetails();
$orderArticles = $orderArticle->fetchValues($order['id'], 'orderid');
$quantity;
$total;
$discount;

//Set values for each ordered product
foreach ($orderArticles as $art) {                  
    $prodid = ($art['product'] - 1);
    $quantity[$prodid] = intval($art['quantity']);
    $total[$prodid] = intval($art['totalamount']);
    $discount[$prodid] = intval($art['totaldiscount']);  
}
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Styles -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <title>Update order</title>
    </head>
    <body>
    <?php 
        include_once('templates/header.php');
       
       $id = $productname = $unitprice = $prize ='';

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
            
            //Update order with new order info
            $newOrder = new Orders();
            $newOrder->setAttributes($orderInfo);
            $updated = $newOrder->update($orderId);
            

            //Check if order was successfully updated
            if($updated != false){
                $newOrderDetail = new OrderDetails();
                //Return all previous products
                $old_details = $newOrderDetail->fetchValues($orderId,'orderid');
              
                $status = [];
                //Compare all new and all old products
                foreach($orderItems as $orderitem){ 
                    foreach($old_details as $olditem){ 
                        
                        if($orderitem['product'] == $olditem['product']){
                            $status[$olditem['product']] = 'updated';
                            //Case 1 old product is being updated
                            $newOrderDetail->setAttributes($orderitem);
                            $newOrderDetail->update($olditem['id']);
                            break; 
                        }   
                         
                    }

                    //If the product isn't in the old products it means it is a new one
                    if(isset($status[$orderitem['product']]) != TRUE){
                        //Case 2 new product is being added
                        $orderitem = array_merge($orderitem, ['orderid' => $orderId]);
                        $newOrderDetail->setAttributes($orderitem);
                        $newOrderDetail->save();

                    }
                   
                }
                // Case 3 check if there are any deleted items
                foreach($old_details as $olditem){ 
                     //If an old product hasn't been updated it means it was deleted
                    if(isset($status[$olditem['product']]) != TRUE)  {      
                        //Case 3 product is removed
                        $newOrderDetail->delete($olditem['id']); 
                    }
                }

                //Create a success notification
            $success['order'] = '<div class="alert alert-success alertSuccess alert-dismissible fade show" role="alert">
            Order successfully updated. <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
         </button>
        </div>';

                
                //Print success notification
                echo $success['order'];
            }

         }

            
            
       }

      
    ?>
    <div class="container">  
        <div clss="row"><h1 class="col-md-6" style="margin: 2rem 0;">Update order</h1></div> 
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