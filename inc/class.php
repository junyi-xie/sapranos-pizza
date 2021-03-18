<?php
    /* Copyright (c) - 2021 by Junyi Xie */	
    
    namespace Sopranos;

    /**
     * Sopranos Pizzabar Orders Class.
     *     
     * @author Junyi Xie
     * @version 1.1 [UPDATED TO AUTOMATICALLY RUN]
     */
    class Orders 
    {

        /**
         * Website base URL.
         */
        const BASE_URL = 'http://localhost:8080/sopranos/';


        /**
         * The order number.
         *
         * @var int
         */
        private $number;


        /**
         * Coupon id.
         *
         * @var int
         */
        private $coupon;


        /**
         * The customer order(s) put in an array. 
         *
         * @var array
         */
        private $order;


        /**
         * The customer data put in an array.
         *
         * @var array
         */
        private $customer;


        /**
         * Total Price.
         *
         * @var float
         */
        private $price;


        /**
         * Customer Inserted Id.
         *
         * @var int
         */
        private $_customer_id;


        /**
         * Order Inserted Id.
         *
         * @var int
         */
        private $_order_id;


        /**
         * Pizza Size Inserted Id.
         *
         * @var int
         */
        private $_size_id;


        /**
         * Pizza Topping Inserted Id.
         *
         * @var int
         */
        private $_topping_id;


        /**
         * Pizza Type Inserted Id.
         *
         * @var int
         */
        private $_type_id;


        /**
         * Pizza Order Inserted Id.
         *
         * @var int
         */
        private $_pizza_id;


        /**
         * Pizza Quantity.
         *
         * @var int
         */
        private $_pizza_quantity;


        /**
         * PDO.
         *
         * @var object
         */
        private $pdo;


        /**
         * Default constructor.
         * 
         * @param array $config
         * @param object $pdo
         *
         * @return void
         * 
         * @throws \Exception Config data is not complete.
         */
        public function __construct($config, $pdo) 
        {

                if(is_object($pdo) && !is_null($pdo)) {
                    $this->setPDO($pdo);
                }
                

            if(is_array($config) && array_key_exists('number', $config) && array_key_exists('coupon', $config) && array_key_exists('order', $config) && array_key_exists('customer', $config)) {
                $this->setNumber($config['number']);
                $this->setCoupon($config['coupon']);
                $this->setOrder($config['order']);
                $this->setCustomer($config['customer']);

                /* [DO NOT CHANGE ORDER, THE FUNCTIONS NEED TO BE CALLED IN THIS SPECIFIC ORDER TO FUNCTION PROPERLY] */
                $this->insertCustomerData();
                $this->insertOrderData();
                $this->setPizzaData();
                $this->applyCoupon(); 
                // $this->createInvoice();
            } else {
                throw new \Exception('Error: __construct() - Configuration data is missing...');
            }
        }


        /**
         * Get the id for the coupon code if it matches the code used by the customer, else do nothing.
         * 
         * @param string $coupon_code
         * 
         * @return boolean
         * 
         * @throws \Exception Coupon code is not a string.
         */
        protected function setCouponCode($coupon_code = '') 
        {

            if(is_string($coupon_code)) {
                $sSql = "
                    SELECT id FROM coupons
                        WHERE 1
                        AND quantity > 0 
                        AND code = :coupon_code
                        LIMIT 1
                ";

                $aCouponSql = $this->pdo->prepare($sSql);

                if(!empty($coupon_code)) {

                    $coupon_code = preg_replace('/[^\da-z_]/i', '', $coupon_code);

                    $aCouponSql->bindParam(':coupon_code', $coupon_code);
                    $aCouponSql->execute();

                    $aCouponId = $aCouponSql->fetch(\PDO::FETCH_ASSOC);

                    if($aCouponSql->rowCount() > 0) {

                        $this->setCoupon($aCouponId['id']);

                        return true;
                    }   

                    return false;
                }
                
                return false;
            }

            throw new \Exception('Error: setCouponCode() - Empty coupon code...');
        } 


        /**
         * Check for duplicate ordernumber inside the order table.
         * 
         * @return boolean
         * 
         * @throws \Exception Table doesn't exist.
         */
        protected function checkOrderNumber() 
        {

            if($this->checkTableExists('orders')) {

                $sSql = "
                    SELECT order_number FROM orders 
                        WHERE 1
                        AND order_number = '".$this->getNumber()."'
                ";

                $aOrderNumberSql = $this->pdo->query($sSql);
                $aOrderNumberSql->fetchAll(\PDO::FETCH_ASSOC);

                if($aOrderNumberSql->rowCount() > 0) {
                    return true;
                } 

                return false;
            }

            throw new \Exception('Error: checkTableExists() - Table (orders) does not exist...');
        }

        
        /**
         * Insert the customer data into the database.
         * 
         * @return void
         * 
         * @throws \Exception The query did not function properly, check if all the values are filled in.
         */
        private function insertCustomerData() 
        {

            $sSql = "
                INSERT INTO customers 
                SET 
                    first_name = :first_name, 
                    last_name = :last_name, 
                    email = :email, 
                    phone = :phone, 
                    address = :address, 
                    address_2 = :address_2, 
                    zipcode = :zipcode, 
                    country = :country, 
                    city = :city,
                    province = :province
            ";

            $aInsertSql = $this->pdo->prepare($sSql);

                foreach ($this->getCustomer() as $key => &$val) {
                    $aInsertSql->bindParam($key, $val);
                }

            $aInsertSql->execute();

                if(!$aInsertSql) {
                    throw new \Exception('Error: insertCustomerData() - Query execute failed... The customer has potentially not filled in all the fields...');
                }

            return $this->setCustomerId($this->pdo->lastInsertId());    
        }


        /**
         * Insert into the orders table with the last inserted id for customer, coupon id and other data.
         * 
         * @return void
         * 
         * @throws \Exception Query failed, check if all values got assigned to the right placeholder.
         */
        private function insertOrderData() 
        {

            if($this->checkOrderNumber()) {
                $this->setNumber(generateUniqueId());
            } 

            $sSql = "
                INSERT INTO orders 
                SET 
                    customer_id = :customer_id, 
                    coupon_id = :coupon_id, 
                    order_number = :order_number, 
                    check_in = :check_in, 
                    check_out = :check_out, 
                    order_status = :order_status 
            ";

            $aInsertSql = $this->pdo->prepare($sSql);

            $aInsertSql->bindValue(':customer_id', $this->getCustomerId());
            $aInsertSql->bindValue(':coupon_id', $this->getCoupon());
            $aInsertSql->bindValue(':order_number', $this->getNumber());
            $aInsertSql->bindValue(':check_in', date("YmdHis"));
            $aInsertSql->bindValue(':check_out', date("YmdHis"));
            $aInsertSql->bindValue(':order_status', 0);

                if(!is_null($this->getCoupon()) && $this->getCoupon() > 0) {
                    $this->updateCoupon($this->getCoupon());
                }
                
            $aInsertSql->execute();

                if(!$aInsertSql) {
                    throw new \Exception('Error: insertOrderData() - Query execute failed... Are all values assigned to their proper placeholder...');
                }            

            return $this->setOrderId($this->pdo->lastInsertId());    
        }


        /**
         * Update the coupon table when a code has been used.
         * 
         * @param int $coupon_id
         *
         * @return boolean
         * 
         * @throws \Exception Could not execute query, the id is either wrong or it's an invalid number. || Table doesn't exist
         */
        private function updateCoupon($coupon_id = 0) 
        {

            if($this->checkTableExists('coupons')) {

                $sSql = "
                    UPDATE coupons 
                    SET 
                        quantity = quantity - 1 
                        WHERE 1 
                        AND quantity > 0
                        AND id = :coupon_id
                        LIMIT 1
                ";   

                $aUpdateSql = $this->pdo->prepare($sSql);
                
                $aUpdateSql->BindParam(':coupon_id', $coupon_id);
                $aUpdateSql->execute();

                    if(!$aUpdateSql) {
                        throw new \Exception('Error: updateCoupon() - Query execute failed... Might be invalid coupon_id...');
                    }

                return true;
            }

            throw new \Exception('Error: checkTableExists() - Table (coupons) does not exist...');
        }


        /**
         * Bind the pizza data to their respective setter, once done, call the insertPizzaOrder() function and insert the data.
         * 
         * @return void
         * 
         * @throws \Exception Foreach failed, certain data is missing.
         */
        private function setPizzaData() 
        {

            $iStatus = false;

            foreach($this->getOrder() as $key => $val) {
                $this->setSizeId($val['size_id']);
                $this->setTypeId($val['type_id']);
                $this->setPizzaQuantity($val['quantity']);

                    if($this->checkTableExists('pizzas_size') && $this->checkTableExists('pizzas_type') ) {
                        $this->setPrice($this->selectPrice('pizzas_size', $this->getSizeId()), $this->getPizzaQuantity());
                        $this->setPrice($this->selectPrice('pizzas_type', $this->getTypeId()), $this->getPizzaQuantity());
                    }
                
                if(!empty($this->getSizeId()) && !empty($this->getTypeId()) && !empty($this->getPizzaQuantity())) {
                    $this->insertPizzaOrder();
                }

                if(!empty($val['topping_id'])) {
                    foreach($val['topping_id'] as $iToppingId => $sToppingName) {
                        $this->setToppingId($iToppingId);

                            if($this->checkTableExists('pizzas_topping')) {
                                $this->setPrice($this->selectPrice('pizzas_topping', $this->getToppingId()), $this->getPizzaQuantity());
                            }

                        if(!empty($this->getPizzaId()) && !empty($this->getToppingId())) {
                            $this->insertToppingCombination();
                        }
                    }
                }

                $iStatus = true;
            }

            if(!$iStatus) {
                throw new \Exception('Error: setPizzaData() - Something went wrong while setting the pizza data... Check the foreach loop...');
            }
        }


        /**
         * Update the pizza_topping and pizza_type tables for the quantity values.
         * 
         * @param string $sTable
         *
         * @return boolean
         * 
         * @throws \Exception Something went wrong... Table could either not be found or getters were empty.
         */
        private function updatePizzaValue($sTable = '') 
        {

            if(!empty($sTable) && is_string($sTable)) {

                $sSql = "
                    UPDATE $sTable
                    SET
                        quantity = quantity - :quantity 
                        WHERE 1 
                        AND quantity > 0
                        AND id = :id
                        LIMIT 1
                ";   

                $aUpdateSql = $this->pdo->prepare($sSql);

                $aUpdateSql->bindValue(':quantity', $this->getPizzaQuantity());

                switch ($sTable) {
                    case 'pizzas_topping':
                        $aUpdateSql->bindValue(':id', $this->getToppingId());
                    break;
                    case 'pizzas_type':
                        $aUpdateSql->bindValue(':id', $this->getTypeId());
                    break;
                }

                $aUpdateSql->execute();

                    if(!$aUpdateSql) {
                        throw new \Exception('Error: updatePizzaValue() - Could not execute query, getters might be empty...');
                    }

                return true;
            }

            throw new \Exception('Error: updatePizzaValue() - The given parameter(s) were not strings...');
        }


        /**
         * Insert the available pizza data into the orders_pizza table.
         * 
         * @return void
         * 
         * @throws \Exception Query failed, certain parameters are missing inside the getters.
         */
        private function insertPizzaOrder() 
        {

            $sSql = "
                INSERT INTO orders_pizza
                SET
                    order_id = :order_id,
                    size_id = :size_id,
                    type_id = :type_id,
                    quantity = :quantity,
                    status = :status
            ";   

            $aInsertSql = $this->pdo->prepare($sSql);

            $aInsertSql->bindValue(':order_id', $this->getOrderId());
            $aInsertSql->bindValue(':size_id', $this->getSizeId());
            $aInsertSql->bindValue(':type_id', $this->getTypeId());
            $aInsertSql->bindValue(':quantity', $this->getPizzaQuantity());
            $aInsertSql->bindValue(':status', 0);

                if($this->checkTableExists('pizzas_type')) {
                    $this->updatePizzaValue('pizzas_type');
                } 

            $aInsertSql->execute();
                
                if(!$aInsertSql) {
                    throw new \Exception('Error: insertPizzaOrder() - Query execute failed...');
                } 

            return $this->setPizzaId($this->pdo->lastInsertId());
        }


        /**
         * Insert the topping id and pizza order id into the topping_combination table.
         * 
         * @return boolean
         * 
         * @throws \Exception Missing data which causes the query not to function properly.
         */
        private function insertToppingCombination() 
        {

            if(!empty($this->getPizzaId()) && !empty($this->getToppingId())) {

                $sSql = "
                    INSERT INTO toppings_combination
                    SET
                        pizza_id = :pizza_id,
                        topping_id = :topping_id
                ";   

                $aInsertSql = $this->pdo->prepare($sSql);

                $aInsertSql->bindValue(':pizza_id', $this->getPizzaId());
                $aInsertSql->bindValue(':topping_id', $this->getToppingId());

                    if($this->checkTableExists('pizzas_topping')) {
                        $this->updatePizzaValue('pizzas_topping');
                    }

                $aInsertSql->execute();

                return true;
            }

            throw new \Exception('Error: insertToppingCombination() - Getters for this function might be empty...');            
        }


        /**
         * Select the count from information_schema.tables to see whether the given table exist.
         * 
         * @param string $sTableName
         * 
         * @return boolean
         * 
         * @throws \Exception Table name error, possibly the given variable was not a string or empty.
         */
        protected function checkTableExists($sTableName = '')
        {

            if(!empty($sTableName) && is_string($sTableName)) {

                $sTableName = preg_replace('/[^\da-z_]/i', '', $sTableName);

                $sSql = "
                    SELECT COUNT(*) AS `count` FROM information_schema.tables 
                        WHERE 1 
                        AND TABLE_SCHEMA = database()
                        AND TABLE_NAME = :table_name
                ";

                $aCheckTableSql = $this->pdo->prepare($sSql);
                $aCheckTableSql->BindParam(':table_name', $sTableName);
                $aCheckTableSql->execute();

                $Output = $aCheckTableSql->fetch(\PDO::FETCH_ASSOC);

                if($Output['count'] > 0) {
                    return true;
                } 

                return false;
            }
            
            throw new \Exception('Error: checkTableExists() - Did you properly input the desired table name...');  
        }


        /**
         * Select the price for assigned table with the given id.
         * 
         * @param string $table
         * @param int $id
         * 
         * @return float
         * 
         * @throws \Exception Potentially database corruption or the column 'price' is empty.
         */
        protected function selectPrice($table = '', $id = 0)
        {

            $sSql = "
                SELECT price FROM $table
                    WHERE 1
                    AND id = :id
                    LIMIT 1
            ";

            $aPriceSql = $this->pdo->prepare($sSql);
            $aPriceSql->execute(array(':id' => $id));
            $Output = $aPriceSql->fetch(\PDO::FETCH_ASSOC);

            if(!empty($Output['price']) && $Output['price'] > 0) {

                return number_format((float)$Output['price'], 2, '.', '');
            }

            throw new \Exception('Error: selectPrice() - Price value is not being displayed properly... Check database data type...');  
        } 


        /**
         * Apply the coupon code to the total price, when applied return true, else false.
         * 
         * @return boolean
         * 
         * @throws \Exception Coupon id is missing or coupon code used is out of stock.
         */
        protected function applyCoupon()
        {

            if(!empty($this->getCoupon()) && is_numeric($this->getCoupon()) && $this->getCoupon() > 0) {

                $sSql = "
                    SELECT * FROM coupons
                        WHERE 1
                        AND id = '".$this->getCoupon()."'
                        AND quantity > 0
                        LIMIT 1
                ";

                $aCoupon = $this->pdo->query($sSql);
                $Output = $aCoupon->fetch(\PDO::FETCH_ASSOC);

                    if(!$Output) {
                        throw new \Exception('Error: applyCoupon() - Coupon is out of stock or id is missing...');  
                    }   

                if($aCoupon->rowCount() > 0) {
                    
                    switch ($Output['type']) {
                        case 1: # percentage
                            $iTotalPrice = $this->getPrice() - ($this->getPrice() * ($Output['discount'] / 100));                        
                        break;
                        case 2: # money
                            $iTotalPrice = $this->getPrice() - $Output['discount'];
                        break;
                    }

                        $this->setTotalPrice($iTotalPrice);

                    return true;
                }

                return false; 
            }
        } 


        /**
         * Create an invoice with the appropriate getters and other data. Make it well structured and easy to read.
         */
        public function createInvoice()
        {
            // TO DO.

            // LINK SOME PDF LIBRARY TO USE
            // AND LINK PHPMAILER TO SEND IT TO THEIR EMAIL
        }


        /**
         * Total Price Setter.
         *
         * @param float $price
         * 
         * @return void
         */
        private function setTotalPrice($price = 0.00) 
        {
            $this->price = $price;
        }


        /**
         * Price Setter for each item.
         *
         * @param float $price
         * @param int $quantity
         * 
         * @return void
         */
        private function setPrice($price = 0.00, $quantity = 0) 
        {
            $this->price += $price * $quantity;
        }


        /**
         * Total Price Getter.
         *
         * @return float
         */
        public function getPrice() 
        {
            return number_format((float)$this->price, 2, '.', '');
        }


        /**
         * Order Number Setter.
         *
         * @param int $number
         * 
         * @return void
         */
        private function setNumber($number = 0)
        {
            $this->number = $number;
        }


        /**
         * Order Number Getter.
         *
         * @return int
         */
        public function getNumber()
        {
            return $this->number;
        }


        /**
         * Customer Data Setter.
         *
         * @param array $customer
         * 
         * @return void
         */
        private function setCustomer($customer = array()) 
        {
            $this->customer = $customer;
        }


        /**
         * Customer Data Getter.
         *
         * @return array
         */
        public function getCustomer() 
        {
            return $this->customer;
        }


        /**
         * Coupon Setter.
         *
         * @param int $coupon
         * 
         * @return void
         */
        private function setCoupon($coupon = 0) 
        {
            $this->coupon = $coupon;
        }


        /**
         * Coupon Getter.
         *
         * @return int
         */
        public function getCoupon() 
        {
            return $this->coupon;
        }


        /**
         * Customer Order Setter.
         *
         * @param array $order
         * 
         * @return void
         */
        private function setOrder($order = array()) 
        {
            $this->order = $order;
        }


        /**
         * Customer Order Getter.
         *
         * @return array
         */
        public function getOrder() 
        {
            return $this->order;
        }


        /**
         * Last inserted Customer Id Setter.
         *
         * @param int $customer_id
         * 
         * @return void
         */
        private function setCustomerId($customer_id = 0) 
        {
            $this->_customer_id = $customer_id;
        }


        /**
         * Last inserted Customer Id Getter.
         *
         * @return int
         */
        public function getCustomerId() 
        {
            return $this->_customer_id;
        }


        /**
         * Last inserted Order Id Setter.
         *
         * @param int $order_id
         * 
         * @return void
         */
        private function setOrderId($order_id = 0) 
        {
            $this->_order_id = $order_id;
        }


        /**
         * Last inserted Order Id Getter.
         *
         * @return int
         */
        public function getOrderId() 
        {
            return $this->_order_id;
        }


        /**
         * Last inserted Pizza Size Id Setter.
         *
         * @param int $size_id
         * 
         * @return void
         */
        private function setSizeId($size_id = 0) 
        {
            $this->_size_id = $size_id;
        }


        /**
         * Last inserted Pizza Size Id Getter.
         *
         * @return int
         */
        public function getSizeId() 
        {
            return $this->_size_id;
        }


        /**
         * Last inserted Pizza Topping Id Setter.
         *
         * @param int $topping_id
         * 
         * @return void
         */
        private function setToppingId($topping_id = 0) 
        {
            $this->_topping_id = $topping_id;
        }


        /**
         * Last inserted Pizza Topping Id Getter.
         *
         * @return int
         */
        public function getToppingId() 
        {
            return $this->_topping_id;
        }


        /**
         * Last inserted Pizza Type Id Setter.
         *
         * @param int $type_id
         * 
         * @return void
         */
        private function setTypeId($type_id = 0) 
        {
            $this->_type_id = $type_id;
        }


        /**
         * Last inserted Pizza Type Id Getter.
         *
         * @return int
         */
        public function getTypeId() 
        {
            return $this->_type_id;
        }


        /**
         * Last inserted Pizza Order Id Setter.
         *
         * @param int $pizza_id
         * 
         * @return void
         */
        private function setPizzaId($pizza_id) 
        {
            $this->_pizza_id = $pizza_id;
        }


        /**
         * Last inserted Pizza Order Id Getter.
         *
         * @return int
         */
        public function getPizzaId() 
        {
            return $this->_pizza_id;
        }


        /**
         * Pizza Quantity Setter.
         *
         * @param int $pizza_quantity
         * 
         * @return void
         */
        private function setPizzaQuantity($pizza_quantity) 
        {
            $this->_pizza_quantity = $pizza_quantity;
        }


        /**
         * Pizza Quantity Getter.
         *
         * @return int
         */
        public function getPizzaQuantity() 
        {
            return $this->_pizza_quantity;
        }


        /**
         * PDO Setter.
         *
         * @param object $pdo
         * 
         * @return void
         */
        private function setPDO($pdo) 
        {
            $this->pdo = $pdo;
        }


        /**
         * Class Destructor.
         *
         * @return boolean
         */
        public function __destruct() 
        {
            return clearSession();
        }
    }
?>