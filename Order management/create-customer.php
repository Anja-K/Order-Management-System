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
        <title>Create a customer</title>
    </head>
    <body>
     <?php  
        include_once('templates/header.php');
        require 'db/db.php';

        
        $customer = new Customers();
        $email = $fullname = $type = '';

        if(isset($_POST['submit'])){
            $email = trim($_POST['email']);
            $fullname =  trim($_POST['fullname']);
            $type = trim($_POST['type']);
            $errors = [];
            $success = [];

            if($email == ''){
                $errors['email'] = "Please add an email";
            }else if($customer->fetchValues($email, 'email') == TRUE){
                $errors['notvalid'] = 'Customer already exists';
            }

            if($fullname == ''){
                $errors['fullname'] = "Customer name is required";
            }

            if($type == ''){
                $errors['type'] = "Please select customer type";
            }


            if(empty($errors)){
                unset($_POST['submit']);
                $newCustomer = new Customers();
                $newCustomer->setAttributes($_POST);
                $success['customer'] = '<div class="alert alert-success alertSuccess alert-dismissible fade show" role="alert">
                        Customer created. <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>';
                if ($newCustomer->save() != false) {
                    echo $success['customer'];
                }
                $email = $fullname = $type = '';
            }

        }
    ?>
     <div class="container">  
        <div clss="row"><h1 class="col-md-6 mx-auto" style="margin: 2rem 0;">Add a new customer</h1></div>    
        <div class="row">
            <form class="col-md-6 mx-auto" method="POST">
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?=$email ?>" placeholder="name@example.com">
                    <small id="emailHelp" class="form-text text-muted"><?= isset($errors['email']) ? $errors['email'] : ''; ?><?= isset($errors['notvalid']) ? $errors['notvalid'] : ''; ?></small>
                </div>
                <div class="form-group">
                    <label for="fullname">Full name</label>
                    <input type="text" class="form-control" id="fullname" name="fullname" value="<?=$fullname ?>" placeholder="Name Surname">
                    <small id="nameHelp" class="form-text text-muted"><?= isset($errors['fullname']) ? $errors['fullname'] : ''; ?></small>
                </div>
                <div class="form-group">
                    <label for="type">Type</label>
                    <select class="form-control" id="type" name="type">
                        <option <?php if($type == '') { echo 'selected=""'; } ?> value="">Select customer type</option>
                        <option <?php if($type == '1') { echo 'selected=""'; } ?> value="1">Private person</option>
                        <option <?php if($type == '2') { echo 'selected=""'; } ?> value="2">Small company</option>
                        <option <?php if($type == '3') { echo 'selected=""'; } ?> value="3">Large company</option>
                    </select>
                    <small id="typeHelp" class="form-text text-muted"><?= isset($errors['type']) ? $errors['type'] : ''; ?></small>
                </div>
                <button name="submit" type="submit" class="btn btn-primary mb-2">Add customer</button>
            </form>
        </div>
    </div>
  
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
   
    </body>

</html>