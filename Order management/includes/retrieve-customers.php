<?php 
        namespace Orders;
        require '../db/db.php';
        
        if(isset($_POST["getDiscountType"])){
            $customer = new Customers();
            $customers = $customer->fetchValues($_POST['id'],'id');
           // var_dump($customers);exit;
             echo json_encode($customers);
             exit();
        }
       
        
     ?> 