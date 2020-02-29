<div class="row">
            <form class=" col-md-6" method="POST">
                <div class="form-group">
                    <label for="customer">Customer</label>
                    <select class="form-control" id="customer" name="customer">
                        <option <?php if($buyer == '') { echo 'selected=""'; } ?> value="">Select customer</option>
                        <?php foreach ($customers as $cust){
                            $sel  ='';
                            if($buyer == $cust['id']){$sel = 'selected';}
                            echo'<option '.$sel.' value="'.$cust['id'].'">'.$cust['fullname'].'</option>';
                        }?>
                    </select>
                    <small id="customerHelp" class="form-text text-muted"><?= isset($errors['customer']) ? $errors['customer'] : ''; ?></small>
                </div>
                <table class="table table-hover table-striped users">
                    <thead class="thead">
                        <tr>
                            <th>Product id</th>
                            <th>Product name</th>
                            <th>Unit price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Discount</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php  
                      
                        $val = 0;
                        foreach ($products as $key) {
                            
                            $id = $key['id'];
                            $productname = $key['productname'];
                            $unitprice = $key['unitprice'];  
                          
                    ?>
                        <tr>
                            <td><input readonly="readonly" type="text" name="id[]"  value="<?= $id; ?>"></td>
                            <td><input readonly="readonly" type="text" class="productname" name="productname[]" value="<?= $productname; ?>"></td>
                            <td><input readonly="readonly" type="text" class="unitprice" name="unitprice[]" value="<?= $unitprice; ?>"></td>
                            <td><input type="number" class="quantity" name="quantity[]"  value="<?= isset($quantity[$val]) ? $quantity[$val] : ''; ?>" min="0" max="100"></td>
                            <td><input readonly="readonly" type="text" class="total" name="total[]" value="<?= isset($total[$val]) ? $total[$val] : ''; ?>"></td>
                            <td><input readonly="readonly" type="text" class="discount" name="discount[]" value="<?= isset($discount[$val]) ? $discount[$val] : ''; ?>"><span class="discountPercentage"><?= isset($discountpercentage[$val]) ? $discountpercentage[$val] : '0'; ?></span><span>%</span></td>
                        </tr>
                        
                    <?php $val++; }?>
                    </tbody>
                </table>
                <small id="totalamountHelp" class="form-text text-muted"><?= isset($errors['totalamount']) ? $errors['totalamount'] : ''; ?></small>
                <div class="form-group">
                    <label for="totalamount">Total amount</label>
                    <input readonly="readonly" type="text" class="form-control" id="totalamount" name="totalamount" value="<?= $totalamount; ?>" placeholder="0">
                </div>
                <div class="form-group">
                    <label  for="totaldiscount">Total discount</label>
                    <input readonly="readonly" type="text" class="form-control" id="totaldiscount" name="totaldiscount" value="<?= $totaldiscount; ?>" placeholder="0">
                </div>
                <div class="form-group">
                    <label for="paidamount">Paid amount</label>
                    <input readonly="readonly" type="text" class="form-control" id="paidamount" name="paidamount"  value="<?= $paidamount; ?>" placeholder="0">
                </div>
                <div class="form-group">
                    <input readonly="readonly" type="hidden" class="form-control" id="prize" name="prize"  value="<?= ($prize != '') ? $prize : '0'; ?>">
                </div>
               
                <button name="submit" type="submit" class="btn btn-primary mb-2">Submit</button>
            </form>
        </div>