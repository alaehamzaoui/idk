<?php
class Product {
   public $product_id;
   public $product_name;
   public $unit_price;




   function __construct($product_id , $product_name , $unit_price) {
    $this->product_id = $product_id;
    $this->product_name = $product_name;
    $this->unit_price =$unit_price;

     
   }

 
}


?>