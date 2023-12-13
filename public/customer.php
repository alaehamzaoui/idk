<?php 
class Customer{
    public $customer_id;
    public $customer_name ;
    public $customer_profit ;

    function __construct($customer_id , $customer_name , $customer_profit) {
        $this->customer_id=$customer_id;
        $this->customer_name=$customer_name;
        $this->customer_profit=$customer_profit;

    }
    
}

?>