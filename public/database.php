<?php
include("product.php");
include("customer.php");

/*
    Class implementing Singleton pattern to get a cursor to the current database.
*/
class MysqlDatabase {

    /* cursor to DB connection */
    private $cursor = null;

    /* Singleton instance - not needed in class methods */
    private static $instance = null;

    /*
        Use this method to get access to the database connection.
    */
    public static function get_instance(){
        if(self::$instance == null){
            self::$instance = new MysqlDatabase();
        }
        return self::$instance;
    }

    /*
        Private constructor to implement Singleton. Do not use this method for instatiation!
    */
	private function __construct(){
		$host = '127.0.0.1';
		$db = 'realdb';
		$user = 'wt1_prakt';
		$pw = 'abcd';
		
		$dsn = "mysql:host=$host;port=3306;dbname=$db";
		
		$options = [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_CASE => PDO::CASE_NATURAL
		];

		try{
            $this->cursor = new PDO($dsn, $user, $pw, $options);
		} 
		catch(PDOException $e){
			echo "Verbindungsaufbau gescheitert: " . $e->getMessage();
		}
    }
    
    /*
        Do not call this method directly.
    */
	public function __destruct(){
		$this->cursor = NULL;	
    }
    public function  read_products(){
        $products=[];
        $DBH=$this->cursor;
        $STH=$DBH->query("SELECT product_id, product_name, unit_price FROM Product");
        while($row = $STH->fetch()) {
             $id =$row['product_id'] ;
             $name=$row['product_name'] ;
             $pri =$row['unit_price'] ;
             $pro = new Product($id,$name , $pri);
             array_push($products , $pro);

        }
        return $products;
    }
    
    public function update_product($id ,$newprice){
        $conn=$this->cursor;

        try {
            $stmt = $conn->prepare('UPDATE Product SET unit_price = :unit_price WHERE product_id = :id');

            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':unit_price' ,$newprice, PDO::PARAM_STR);

            
            $stmt->execute();
         
        } catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }


    }
    // public function read_customers(){
    //     $DBH=$this->cursor;
    //     $customers = [];
        
    //     $STH = $DBH->query('SELECT  customer_id, customer_name, customer_profit FROM Customer');
   
    //     # setting the fetch mode
    //     $STH->setFetchMode(PDO::FETCH_ASSOC);
         
    //     while($row = $STH->fetch()) {
         
    //         $customer = new Customer($row['customer_id'] , $row['customer_name'] , $row['customer_profit']);
    //         array_push($customers , $customer);

    //     }
    //     return $customers;
    // }
    // public function  read_customers(){
    //     $customers = [];
    //     $DBH=$this->cursor;
    //     $STH=$DBH->query("SELECT customer_id, customer_name, customer_profit FROM Customer");
    //     while($row = $STH->fetch()) {
    //          $id =$row['customer_id'] ;
    //          $name=$row['customer_name'] ;
    //          $pri =$row['customer_profit'] ;
    //          $customer = new Customer($id , $name ,$pri );

    //          array_push($customers , $customer);


    //     }
    //     return $customers;
    // }
    public function read_customers() {
        $customers = [];

        try {
            $STH = $this->cursor->query('SELECT customer_id, customer_name, customer_profit FROM Customer');

            // Setting the fetch mode
            $STH->setFetchMode(PDO::FETCH_ASSOC);

            while ($row = $STH->fetch()) {
                $customer = new Customer($row['customer_id'], $row['customer_name'], $row['customer_profit']);
                array_push($customers, $customer);
            }
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }

        return $customers;
    }

    
    
		
}




?>
