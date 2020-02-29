<?php namespace Orders; ?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Styles -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="css/style.css">
        <title>View customers</title>
    </head>
    <body>
     <?php 
        require 'db/db.php';
        include_once('templates/header.php');
        $customer = new Customers();
        $customers = $customer->fetchValues();
     ?> 
    <div class="container">  
        <div clss="row"><h1 class="col-md-6 mx-auto" style="margin: 2rem 0;">All customers</h1></div>    
        <div class="row">
            <table class="table table-hover table-striped users">
                <thead class="thead">
                    <tr>
                        <th>Customer id</th>
                        <th>Full name</th>
                        <th>e-mail</th>
                        <th>Customer type</th>
                    </tr>
                </thead>
                <tbody>
                <?php  
                    foreach ($customers as $key) {
                        $id = $key['id'];
                        $email = $key['email'];
                        $fullname = $key['fullname'];
                        $type = $key['type'];   
                ?>
                    <tr>
                        <td><?= $id; ?></td>
                        <td><?= $fullname;  ?></td>
                        <td><?= $email ;?></td>
                        <td><?= $type; ?></p>
                    </tr>
                <?php }?>
                </tbody>
            </table>
        </div>
  
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
   
    </body>

</html>