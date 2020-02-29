<?php namespace Orders; ?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Styles -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <title>View customers</title>
    </head>
    <body>
     <?php 
        require 'db/db.php';
        include_once('templates/header.php');
        $order = new Orders();
        $orders = $order->fetchValues();
     ?> 
    <div class="container">  
        <div clss="row"><h1 class="col-md-6" style="margin: 2rem 0;">All orders</h1></div>    

        <?php  
            foreach ($orders as $key) {
                $id = $key['id'];
                $customerid = $key['customer'];
                $totalamount = $key['totalamount'];
                $totaldiscount = $key['totaldiscount']; 
                $prize = $key['prize'];
                $customer = new Customers();
                $customer = $customer->fetchValues($customerid, 'id');
                $customer = $customer[0];
        ?>
        <div class="row">
            <div class="card col-md-12" style="margin: 25px 0;">
                <div class="card-header"> Order <b># <?= $id; ?></b></div>
                <ul class="list-group list-group-flush col-md-12 mx-auto">
                    <li class="list-group-item">Customer: <b><?= $customer['fullname']; ?></b></li>
                    <li class="list-group-item">Total amount: <b><?= $totalamount; ?></b> </li>
                    <li class="list-group-item">Total discount: <b><?= $totaldiscount; ?></b></li>
                    <li class="list-group-item">
                        <p>Ordered products:</p>
                        <table class="table table-sm users">
                            <thead class="thead thead-light">
                                <tr>
                                    <th>Article</th>
                                    <th>Unit price</th>
                                    <th>Quantity</th>
                                    <th>Total amount</th>
                                    <th>Total discount</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php  
                                $orderDetail = new OrderDetails();
                                $orderDetails = $orderDetail->fetchValues($id, 'orderid');
                                    foreach ($orderDetails as $val) {
                                        $product = $val['product'];
                                        $quantity = $val['quantity'];
                                        $totalamount = $val['totalamount'];
                                        $totaldiscount = $val['totaldiscount']; 
                                        $article = new Products();
                                        $article = $article->fetchValues($product, 'id');
                                        $article = $article[0];  
                                ?>
                                    <tr>
                                        <td><?= $article['productname']; ?></td>
                                        <td><?= $article['unitprice']; ?></td>
                                        <td><?= $quantity ;  ?></td>
                                        <td><?= $totalamount; ?></td>
                                        <td><?= $totaldiscount; ?></p>
                                    </tr>
                            <?php }?>
                                
                            </tbody>
                        </table>
                    </li>
                    <?php 
                        if($prize == 1){
                            echo '<li class="list-group-item"><div class="alert alert-primary" role="alert">
                            Customer has won a bike!
                        </div></li>';
                        }
                    ?>
                    <div style="margin: 20px;"><a class="btn btn-primary" href="update.php?id=<?=$id; ?>" role="button">Update order</a></div>
                </ul>  
            </div>      
        </div>
        <?php }?>
        

  
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
   
    </body>

</html>