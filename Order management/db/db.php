<?php

    namespace Orders;
    abstract class DB{

        const DB_HOST = 'localhost';
        const DB_USERNAME = 'root';
        const DB_PASSWORD = '';
        const DB_DB = 'order_management';
        protected $db;
        protected $table;

        function  __construct(){

            try{
                $this->db = new \PDO("mysql:host=" . self::DB_HOST . ";dbname=" . self::DB_DB, self::DB_USERNAME, self::DB_PASSWORD);
               
            }catch(\PDOException $ex){
                echo $ex->getMessage();
            }catch(\Exception $ex){
                echo $ex->getMessage();
            }

        }


        public function save(){

            $properties = get_object_vars($this);
            
            unset($properties['table']);
            unset($properties['db']);
           
            

            $columns = array_keys($properties);
            $values = array_values($properties);

            
            
            $query = "INSERT INTO {$this->table}" . "(" . implode(',' , $columns) . ")" . " VALUES (" . '"' . implode('","', $values) . '"' . " ) ";
            if($this->table == 'orders'){
                $sucess['save'] = $this->db->exec($query);
                $sucess['lastId'] =  $this->db->lastInsertId();
                return $sucess;
                
            }else{
                return $this->db->exec($query);}
            

        }

        public function setAttributes($attributes){

            foreach ($attributes as $key => $value) {
                if(property_exists($this, $key)){
                    $this->{$key} = $value;
                }
            }

        }

        public function fetchValues($val=1, $param=1){
            $query = "SELECT * FROM {$this->table} WHERE {$param} = '$val'";
            $pdoStatementObject = $this->db->query($query, \PDO::FETCH_ASSOC);
            return $pdoStatementObject->fetchAll();
        }


        public function __destruct(){
            $this->db = NULL;
        }


    }

     //Products
     class Products extends DB{
		public $id;
        public $productname;
        public $unitprice;

		function  __construct(){
			parent::__construct();
			$this->table = 'products';
		}

	}

    //Customers
    class Customers extends DB{
		public $email;
        public $fullname;
        public $type;

		function  __construct(){
			parent::__construct();
			$this->table = 'customers';
		}

    }
    
    //Orders
    class Orders extends DB{
		public $customer;
        public $totalamount;
        public $totaldiscount;
        public $paidamount;
        public $prize;

		function  __construct(){
			parent::__construct();
			$this->table = 'orders';
        }

        public function update($id){
			$query = "UPDATE {$this->table} SET ";
        	$query.= " customer = '{$this->customer}',";
        	$query.= " totalamount = '{$this->totalamount}',";
        	$query.= " totaldiscount = '{$this->totaldiscount}',";
            $query.= " paidamount = '{$this->paidamount}',";
            $query.= " prize = '{$this->prize}'";
        	$query.= " WHERE id = $id";

        	return $this->db->exec($query);

		}
        
        

    }

    class OrderDetails extends DB{
		public $orderid;
        public $product;
        public $quantity;
        public $totalamount;
        public $totaldiscount;

		function  __construct(){
			parent::__construct();
			$this->table = 'order_items';
        }

        public function update($id){
            //UPDATE order_management.customers SET type = 'Small Company' WHERE id = 2 AND type = 'Large Company';
			$query = "UPDATE {$this->table} SET ";
        	$query.= " product = '{$this->product}',";
        	$query.= " quantity = '{$this->quantity}',";
            $query.= " totalamount = '{$this->totalamount}',";
            $query.= " totaldiscount = '{$this->totaldiscount}'";
        	$query.= " WHERE id = $id";
        	
        	return $this->db->exec($query);

        }
        
        public function delete($id){

			$query = "DELETE FROM {$this->table} WHERE id = {$id}";
			return $this->db->exec($query);
		}

    }
?> 