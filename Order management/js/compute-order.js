$(document).ready(function() {
            
    var customerDiscounts = [];
    var totalResult;
    var totalResultDiscount;
    var totalDiscount;
    var paidResult;
    var paidAmount;

    $('#customer').on('change paste keyup', function(){
        var customerid = $(this).val();
    
        $.ajax({
            url: 'includes/retrieve-customers.php',
            method: "POST",
            data: {getDiscountType:1, id:customerid},
            dataType: 'json',
            success: function(data){
                var customerType = data[0]['type'];
                customerDiscounts = CalculateDiscount(customerType);
                AddDiscount(...customerDiscounts);
                
                totalResult = $('#totalamount').val();
                totalResultDiscount = CalculateTotalAmount('.discount');
                totalDiscount = $('#totaldiscount').val(totalResultDiscount);
                paidResult = CalculatePaidAmount(totalResult,totalResultDiscount);
                paidAmount = $('#paidamount').val(paidResult);
                UpdatePrize(paidResult);


            }
        });
    });
    

    $('.quantity').on('change paste keyup', function(){
    
        var quantity = $(this).val();
        var tr = $(this).parent().parent();
        var unitprice = tr.find('.unitprice').val();
        var result = CalculateProductTotal(unitprice,quantity);
        var total = tr.find('.total').val(result);

        var discount = tr.find('.discountPercentage').text();
        discount = discount/100;
        var discountResult = CalculateProductTotal(result,discount);
        tr.find('.discount').val(discountResult);

        totalResult = CalculateTotalAmount('.total');
        totalAmount = $('#totalamount').val(totalResult);

        totalResultDiscount = CalculateTotalAmount('.discount');
        totalDiscount = $('#totaldiscount').val(totalResultDiscount);

        paidResult = CalculatePaidAmount(totalResult,totalResultDiscount);
        paidAmount = $('#paidamount').val(paidResult);

        UpdatePrize(paidResult);
      
       
    });

    function CalculateProductTotal(price,quantity){
        return price * quantity;
    }

    function CalculateTotalAmount(param){
        var totalAmount = 0;
        var qtotal = param;
        $(qtotal).each(function(i, obj) {
            var total = $(qtotal +':eq(' + i + ')').val();
            console.log(total);
            if(total == ''){
                total = 0;
            }
            totalAmount += parseInt(total);
        });
        return totalAmount;
        
    }

    function CalculatePaidAmount(totalAmount,totalDiscount){
        return totalAmount - totalDiscount;
    }

    function UpdatePrize(paidResult){
        var success = '<div class="alert alert-success alertSuccess fade show" id="bike-alert" role="alert">'
                + 'Congratulations, you have won a bike. </div>';
        if(paidResult > 10000){
            $('#prize').val('1');
            if($('#bike-alert').length == ''){
                $("form").prepend(success);
           }
        }else{
            $('#prize').val('0');
            if($('#bike-alert').length){
                $('#bike-alert').remove();
            }
        }
    }

    function CalculateDiscount(type){
        var discount = 0; 
        var additionalDiscount = 0;
        var discounts = [];
        if(type == 'Small Company' || type == 'Large Company' ){
            discount = 0.1;
        }
        if(type == 'Large Company'){
            additionalDiscount = 0.2;
        }

        discounts = [discount,additionalDiscount];
        return discounts;
    }

    function AddDiscount(discount, additionalDiscount){
        var discount = discount * 100;
        var additionalDiscount = additionalDiscount * 100;
        
        $('.discountPercentage').each(function(i, obj) {
            var discountPercentage = $('.discountPercentage:eq(' + i + ')');
            var tr = discountPercentage.parent().parent();
            var product = tr.find('.productname').val();
            var price = tr.find('.total').val();
            var productDiscount = tr.find('.discount');

            if(product == 'Pen' || product == 'Paper'){
                var dicsountCalc = discount + additionalDiscount;
                discountPercentage.text(dicsountCalc);

                if(price != ''){
                    productDiscount.val(price * dicsountCalc /100);
                }

            }else{
                discountPercentage.text(discount);
                if(price != ''){
                    productDiscount.val(price * discount /100);
                }
            }

        });

    }

    
})
