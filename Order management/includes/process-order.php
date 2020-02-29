<?php
namespace Orders;

    //Check if user has chosen a customer and products to order
    function validateForm($buyer, $totalamount, $totaldiscount, $paidamount){
        $errors = [];
        if($buyer == ''){
            $errors['customer'] = "Please select a customer";
        }

        if($totalamount == '' || $totalamount == 0 ){
            $errors['totalamount'] = "Please select products to order";
        } 

        if($totaldiscount == ''){
            $_POST['totaldiscount'] = '0';
        }
        return $errors;
    }

    //Calculate discount
    function calculateDiscount($ctype, $pname){
        $disc=0;
        if($ctype == "Small Company" || $ctype == "Large Company" ){
            $disc = 10;
            if($ctype == "Large Company"){
                if($pname == 'Pen' || $pname == 'Paper') {
                    $disc = 30;
                } 
            }
        }
        return $disc;
    }

      //Check if user has won a prize
      function updatePrize($paidamount){
        $prize = 0;
        if($paidamount > 10000 ){
            $prize = 1;
        }
        return $prize;
    }


    //Check what products are ordered and calculate their price and discount
    function computeOrder($postInfo, $cust){

        $totalAm = 0;
        $totalDisc = 0;
        $paidAm = 0;
        $prize = 0;
                
        $orderItems = [];
        $processedOrder = [];

        $item = 0;
        $oitem = 0;

        //Go through all products
        foreach ($postInfo['quantity'] as $qty){
            //Check if customer has added the produt by checking the quantity
            if($qty > 0){
                $prod = new Products();
                $prods = $prod->fetchValues($postInfo['id'][$item], 'id');
                $prods = $prods[0];

                // Calculate discount for current product
                $disc = calculateDiscount($cust['type'], $prods['productname']);  
                
                //Create array to store order item details and assign calculated values for the current product
                $arrayItems = [
                    'product' => $postInfo['id'][$item],
                    'productname' => $prods['productname'],
                    'unitprice' => $prods['unitprice'],
                    'quantity' => $postInfo['quantity'][$item],
                    'totalamount' => ($prods['unitprice'] * $postInfo['quantity'][$item]),
                    'totaldiscount' => ($prods['unitprice'] * $postInfo['quantity'][$item] * $disc /100)
                ];

                //Update order information based on new added product
                $totalAm += ($prods['unitprice'] * $postInfo['quantity'][$item]);
                $totalDisc += ($prods['unitprice'] * $postInfo['quantity'][$item] * $disc /100);
                $paidAm = $totalAm - $totalDisc;
                $prize = updatePrize($paidAm);

                $orderItems[$oitem] =  $arrayItems;
                $oitem++;
            }

            $item++;
        }//end of foreach

        //Create array to store order information and assign calculated values
        $order = [
            'totalamount' => $totalAm ,
            'totaldiscount' => $totalDisc,
            'paidamount' => $paidAm,
            'prize' => $prize
        ];

        //Store Order info and Order items info in one array and return the array
        $processedOrder['order'] = $order;
        $processedOrder['items'] = $orderItems;

        return $processedOrder;

}



?>