<?php
    /* Copyright (c) - 2021 by Junyi Xie */

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'class/PHPMailer/src/Exception.php';
    require 'class/PHPMailer/src/PHPMailer.php';
    require 'class/PHPMailer/src/SMTP.php';

    
    /**
     * print_r but fancier.
     *
     * @param mixed $arr
     *
     * @return string
     */
    function printr($arr) {
        print '<code><pre style="text-align: left; margin: 10px;">'.print_r($arr, TRUE).'</pre></code>';
    }


    /**
     * Get files in given directory with specified extention type.
     *
     * @param string $dir
     * @param string $ext
     *
     * @return array
     */
    function getFiles($dir = 'assets\js', $ext = 'js') {

        $handle = opendir($dir);

        if (!$handle) return array();
        
        $contents = array();

        while ($entry = readdir($handle))   
        {
            if ($entry == '.' || $entry == '..') continue;

            $entry = $dir.DIRECTORY_SEPARATOR.$entry;
            
            if (is_file($entry)) {

                if (preg_match("/\.$ext$/", $entry)) {

                    $contents[] = $entry;

                }

            } else if (is_dir($entry)) {

                $contents = array_merge($contents, getFiles($entry, $ext));

            }
        }

        closedir($handle);
    
        return $contents;
    }

    
    /**
     * Load files from array. This fuction is used with getFiles().
     *
     * @param array $contents
     *
     * @return string
     */
    function loadFiles($contents = array()) {

        $s = '';

        foreach($contents as $file) {

            $ext = pathinfo($file, PATHINFO_EXTENSION);

            switch ($ext) {

                default:
                    $s .= 'silence...';
                break;

                case 'js':
                    $s .= '<script type="text/javascript" src="'.$file.'"></script>';
                break;

                case 'css': 
                    $s .= '<link rel="stylesheet" type="text/css" href="'.$file.'?'.date("YmdHis").'" media="screen">';
                break;

                case 'php':
                    $s .= 'include_once("'.$file.'")';
                break;

            }

        }

        return $s;
    }


    /**
     * Generate uniqueid for order number.
     *
     * @return int
     */
    function generateUniqueId() {

        return hexdec(uniqid());
    }


    /**
     * Set key for session with desired value.
     *
     * @param mixed $key
     * @param mixed $value
     * 
     * @return array
     */
    function saveInSession($key, $value) {

        if(!isset($_SESSION['sopranos'][$key])) {

            $_SESSION['sopranos'][$key] = $value;
    
        } 

        return $_SESSION['sopranos'][$key];
    }


    /**
     * Retreive the desired session with given name.
     *
     * @param mixed $name
     * 
     * @return boolean|array
     */
    function getInSession($name) {

        if (isset($_SESSION[$name])) return $_SESSION[$name];

        return false;
    }


    /**
     * Validate emailadres.
     *
     * @param mixed $email
     * 
     * @return boolean
     */
    function isEmailValid($email){ 

        return filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('/@.+./', $email);
    }


    /**
     * Get all the coupons that have not expired and are valid to use.
     *
     * @param mixed $datetime
     * @param object|null $pdo
     * 
     * @return mixed
     */
    function selectValidCoupons($datetime, $pdo = null) {

        if(empty($pdo)) {
            global $pdo;
        }

        $coupons = $pdo->query("SELECT * FROM coupons WHERE 1 AND valid <= '".$datetime."' AND expire >= '".$datetime."' AND quantity > 0 ORDER BY id DESC");

        return $coupons->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Validate the input code by the customer and see if they match with the codes from database.
     *
     * @param array $coupons
     * @param mixed $code
     * 
     * @return mixed
     */
    function validateCouponCode($coupons = array(), $code) {

        foreach($coupons as $coupon) {

            if($coupon['code'] === strtoupper($code) || $coupon['code'] === strtolower($code)) {

                return $coupon['id'];

            }
        }
        
        return NULL;
    }


    /**
     * Put the customer order in a $_SESSION with the help of saveOrderSession() function.
     *
     * @param mixed $order
     *
     * @return boolean
     */
    function saveCustomerOrder($order) {

        $params = array();
        parse_str($order, $params);
        
        if (!is_array($params)) return false;
                
            saveOrderSession($params);

        return true;
    }


    /**
     * Save the customer order in $_SESSION.
     *
     * @param array $array
     *
     * @return array
     */
    function saveOrderSession($array = array()) {

        $_SESSION['sopranos']['order'][] = $array;

        return $_SESSION['sopranos']['order'];
    }


    /**
     * Search if array exists, if exists increment. [OLD]
     *
     * @param array $array
     *
     * @return array
     */
    function orderArrayExists($array = array()) {

        foreach($array as $key => $val) {

            if(isset($_SESSION['sopranos']['order'][$key])) {
                $key++;

                saveCustomerOrder($val, $key);
            }
        }

        return $array;
    }


    /**
     * Save the customer information like name, adres, email, etc in a $_SESSION for later use.
     *
     * @param mixed $data
     *
     * @return boolean
     */
    function saveCustomerData($data) {

        $params = array();
        parse_str($data, $params);

        $bEmail = isEmailValid($params['customer']['email']);

        $aCoupons = selectValidCoupons(date("YmdHis"));
        $coupon = validateCouponCode($aCoupons, $params['coupon']);

            if (!$bEmail) return false; 
            if (!is_array($params)) return false;

        saveInSession('coupon', $coupon);            
        saveInSession('customer', $params['customer']);            

        return true;
    }

    /**
     * Get all the customer details attached to the given uniqueid.
     * 
     * @param int @uniqueid
     *
     * @return array
     */
    function getCustomer($uniqueid) {

        return $_SESSION[$uniqueid];
    }


    /**
     * Unset session with given name.
     * 
     * @param mixed $key
     * @param string $type
     *
     * @return boolean
     */
    function unsetSession($key, $type = '') {

        if (!is_int($key) && !is_array($key)) return false;

        switch ($type) {
            case 'sopranos_order':
                unset($_SESSION['sopranos']['order'][$key]);
            break;
            default:
                unset($_SESSION[$key]);
            break;
        }

        return true;
    }


    /**
     * Destroy all data that was attached to sessions.
     *
     * @return boolean
     */
    function clearSession() {

        return session_destroy();
    }


    /**
     * Select query to get rows from given table, could also retreive rows with given id.
     *
     * @param string $table
     * @param int|null $id
     * @param mixed|null $pdo
     * 
     * @return mixed
     */
    function selectAllById($table = '', $id = null, $pdo = null) {

        if(empty($pdo)) {
            global $pdo;
        }

            if (!is_string($table)) return false;

        $sql = "SELECT * FROM $table";    

        if (!is_null($id)) {
            $sql .= " WHERE id = $id LIMIT 1";

            return $pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
        }

        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Truncate the desired table, used just to clear data instead of manually deleting records inside the database.
     *
     * @param string $table
     * @param mixed|null $pdo
     * 
     * @return boolean
     */
    function truncateTable($table = '', $pdo = null) {

        if(empty($pdo)) {
            global $pdo;
        }

            if (!is_string($table)) return false;

        $sSql = 'TRUNCATE TABLE `'.$table.'`';
        $aSql = $pdo->query($sSql);

        if(!$aSql) return false;

        return true;
    }


    /**
     * Query handle function. Does the select for you without writing extra code.
     *
     * @param string $sql
     * @param string $join
     * @param string $where
     * @param string $order
     * @param int $limit
     * 
     * @return mixed
     */
    function queryHandler($sql = '', $join = '', $where = '', $order = '', $limit = 0) {

        if(empty($pdo)) {
            global $pdo;
        }


        if (!empty($join)) {
            $sql .= ' LEFT JOIN '. $join;
        } 

        if (!empty($where)) {
            $sql .= ' WHERE 1 AND '. $where;
        } 
        
        if (!empty($order)) {
            $sql .= ' ORDER BY '. $order;
        } 
        
        if (!empty($limit)) {
            $sql .= ' LIMIT '. $limit;
        }


        if ($limit == 1) {
            return $pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
        } else {
            return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } 
    }


    /**
     * Get list of country, used for checkout form.
     *
     * @param string $type
     * 
     * @return string
     */
    function getListCountry($type = 'select') {

        $string = '';

        switch ($type) {
            case 'select':
                $string .= '
                    <option value selected disabled hidden>Select Country...</option>
                    <option value="Afghanistan">Afghanistan</option>
                    <option value="Åland Islands">Åland Islands</option>
                    <option value="Albania">Albania</option>
                    <option value="Algeria">Algeria</option>
                    <option value="American Samoa">American Samoa</option>
                    <option value="Andorra">Andorra</option>
                    <option value="Angola">Angola</option>
                    <option value="Anguilla">Anguilla</option>
                    <option value="Antarctica">Antarctica</option>
                    <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                    <option value="Argentina">Argentina</option>
                    <option value="Armenia">Armenia</option>
                    <option value="Aruba">Aruba</option>
                    <option value="Australia">Australia</option>
                    <option value="Austria">Austria</option>
                    <option value="Azerbaijan">Azerbaijan</option>
                    <option value="Bahamas">Bahamas</option>
                    <option value="Bahrain">Bahrain</option>
                    <option value="Bangladesh">Bangladesh</option>
                    <option value="Barbados">Barbados</option>
                    <option value="Belarus">Belarus</option>
                    <option value="Belgium">Belgium</option>
                    <option value="Belize">Belize</option>
                    <option value="Benin">Benin</option>
                    <option value="Bermuda">Bermuda</option>
                    <option value="Bhutan">Bhutan</option>
                    <option value="Bolivia">Bolivia</option>
                    <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                    <option value="Botswana">Botswana</option>
                    <option value="Bouvet Island">Bouvet Island</option>
                    <option value="Brazil">Brazil</option>
                    <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                    <option value="Brunei Darussalam">Brunei Darussalam</option>
                    <option value="Bulgaria">Bulgaria</option>
                    <option value="Burkina Faso">Burkina Faso</option>
                    <option value="Burundi">Burundi</option>
                    <option value="Cambodia">Cambodia</option>
                    <option value="Cameroon">Cameroon</option>
                    <option value="Canada">Canada</option>
                    <option value="Cape Verde">Cape Verde</option>
                    <option value="Cayman Islands">Cayman Islands</option>
                    <option value="Central African Republic">Central African Republic</option>
                    <option value="Chad">Chad</option>
                    <option value="Chile">Chile</option>
                    <option value="China">China</option>
                    <option value="Christmas Island">Christmas Island</option>
                    <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                    <option value="Colombia">Colombia</option>
                    <option value="Comoros">Comoros</option>
                    <option value="Congo">Congo</option>
                    <option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option>
                    <option value="Cook Islands">Cook Islands</option>
                    <option value="Costa Rica">Costa Rica</option>
                    <option value="Cote D\'ivoire">Cote D\'ivoire</option>
                    <option value="Croatia">Croatia</option>
                    <option value="Cuba">Cuba</option>
                    <option value="Cyprus">Cyprus</option>
                    <option value="Czech Republic">Czech Republic</option>
                    <option value="Denmark">Denmark</option>
                    <option value="Djibouti">Djibouti</option>
                    <option value="Dominica">Dominica</option>
                    <option value="Dominican Republic">Dominican Republic</option>
                    <option value="Ecuador">Ecuador</option>
                    <option value="Egypt">Egypt</option>
                    <option value="El Salvador">El Salvador</option>
                    <option value="Equatorial Guinea">Equatorial Guinea</option>
                    <option value="Eritrea">Eritrea</option>
                    <option value="Estonia">Estonia</option>
                    <option value="Ethiopia">Ethiopia</option>
                    <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                    <option value="Faroe Islands">Faroe Islands</option>
                    <option value="Fiji">Fiji</option>
                    <option value="Finland">Finland</option>
                    <option value="France">France</option>
                    <option value="French Guiana">French Guiana</option>
                    <option value="French Polynesia">French Polynesia</option>
                    <option value="French Southern Territories">French Southern Territories</option>
                    <option value="Gabon">Gabon</option>
                    <option value="Gambia">Gambia</option>
                    <option value="Georgia">Georgia</option>
                    <option value="Germany">Germany</option>
                    <option value="Ghana">Ghana</option>
                    <option value="Gibraltar">Gibraltar</option>
                    <option value="Greece">Greece</option>
                    <option value="Greenland">Greenland</option>
                    <option value="Grenada">Grenada</option>
                    <option value="Guadeloupe">Guadeloupe</option>
                    <option value="Guam">Guam</option>
                    <option value="Guatemala">Guatemala</option>
                    <option value="Guernsey">Guernsey</option>
                    <option value="Guinea">Guinea</option>
                    <option value="Guinea-bissau">Guinea-bissau</option>
                    <option value="Guyana">Guyana</option>
                    <option value="Haiti">Haiti</option>
                    <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
                    <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                    <option value="Honduras">Honduras</option>
                    <option value="Hong Kong">Hong Kong</option>
                    <option value="Hungary">Hungary</option>
                    <option value="Iceland">Iceland</option>
                    <option value="India">India</option>
                    <option value="Indonesia">Indonesia</option>
                    <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
                    <option value="Iraq">Iraq</option>
                    <option value="Ireland">Ireland</option>
                    <option value="Isle of Man">Isle of Man</option>
                    <option value="Israel">Israel</option>
                    <option value="Italy">Italy</option>
                    <option value="Jamaica">Jamaica</option>
                    <option value="Japan">Japan</option>
                    <option value="Jersey">Jersey</option>
                    <option value="Jordan">Jordan</option>
                    <option value="Kazakhstan">Kazakhstan</option>
                    <option value="Kenya">Kenya</option>
                    <option value="Kiribati">Kiribati</option>
                    <option value="Korea, Democratic People\'s Republic of">Korea, Democratic People\'s Republic of</option>
                    <option value="Korea, Republic of">Korea, Republic of</option>
                    <option value="Kuwait">Kuwait</option>
                    <option value="Kyrgyzstan">Kyrgyzstan</option>
                    <option value="Lao People\'s Democratic Republic">Lao People\'s Democratic Republic</option>
                    <option value="Latvia">Latvia</option>
                    <option value="Lebanon">Lebanon</option>
                    <option value="Lesotho">Lesotho</option>
                    <option value="Liberia">Liberia</option>
                    <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                    <option value="Liechtenstein">Liechtenstein</option>
                    <option value="Lithuania">Lithuania</option>
                    <option value="Luxembourg">Luxembourg</option>
                    <option value="Macao">Macao</option>
                    <option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option>
                    <option value="Madagascar">Madagascar</option>
                    <option value="Malawi">Malawi</option>
                    <option value="Malaysia">Malaysia</option>
                    <option value="Maldives">Maldives</option>
                    <option value="Mali">Mali</option>
                    <option value="Malta">Malta</option>
                    <option value="Marshall Islands">Marshall Islands</option>
                    <option value="Martinique">Martinique</option>
                    <option value="Mauritania">Mauritania</option>
                    <option value="Mauritius">Mauritius</option>
                    <option value="Mayotte">Mayotte</option>
                    <option value="Mexico">Mexico</option>
                    <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                    <option value="Moldova, Republic of">Moldova, Republic of</option>
                    <option value="Monaco">Monaco</option>
                    <option value="Mongolia">Mongolia</option>
                    <option value="Montenegro">Montenegro</option>
                    <option value="Montserrat">Montserrat</option>
                    <option value="Morocco">Morocco</option>
                    <option value="Mozambique">Mozambique</option>
                    <option value="Myanmar">Myanmar</option>
                    <option value="Namibia">Namibia</option>
                    <option value="Nauru">Nauru</option>
                    <option value="Nepal">Nepal</option>
                    <option value="Netherlands">Netherlands</option>
                    <option value="Netherlands Antilles">Netherlands Antilles</option>
                    <option value="New Caledonia">New Caledonia</option>
                    <option value="New Zealand">New Zealand</option>
                    <option value="Nicaragua">Nicaragua</option>
                    <option value="Niger">Niger</option>
                    <option value="Nigeria">Nigeria</option>
                    <option value="Niue">Niue</option>
                    <option value="Norfolk Island">Norfolk Island</option>
                    <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                    <option value="Norway">Norway</option>
                    <option value="Oman">Oman</option>
                    <option value="Pakistan">Pakistan</option>
                    <option value="Palau">Palau</option>
                    <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
                    <option value="Panama">Panama</option>
                    <option value="Papua New Guinea">Papua New Guinea</option>
                    <option value="Paraguay">Paraguay</option>
                    <option value="Peru">Peru</option>
                    <option value="Philippines">Philippines</option>
                    <option value="Pitcairn">Pitcairn</option>
                    <option value="Poland">Poland</option>
                    <option value="Portugal">Portugal</option>
                    <option value="Puerto Rico">Puerto Rico</option>
                    <option value="Qatar">Qatar</option>
                    <option value="Reunion">Reunion</option>
                    <option value="Romania">Romania</option>
                    <option value="Russian Federation">Russian Federation</option>
                    <option value="Rwanda">Rwanda</option>
                    <option value="Saint Helena">Saint Helena</option>
                    <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                    <option value="Saint Lucia">Saint Lucia</option>
                    <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                    <option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>
                    <option value="Samoa">Samoa</option>
                    <option value="San Marino">San Marino</option>
                    <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                    <option value="Saudi Arabia">Saudi Arabia</option>
                    <option value="Senegal">Senegal</option>
                    <option value="Serbia">Serbia</option>
                    <option value="Seychelles">Seychelles</option>
                    <option value="Sierra Leone">Sierra Leone</option>
                    <option value="Singapore">Singapore</option>
                    <option value="Slovakia">Slovakia</option>
                    <option value="Slovenia">Slovenia</option>
                    <option value="Solomon Islands">Solomon Islands</option>
                    <option value="Somalia">Somalia</option>
                    <option value="South Africa">South Africa</option>
                    <option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option>
                    <option value="Spain">Spain</option>
                    <option value="Sri Lanka">Sri Lanka</option>
                    <option value="Sudan">Sudan</option>
                    <option value="Suriname">Suriname</option>
                    <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                    <option value="Swaziland">Swaziland</option>
                    <option value="Sweden">Sweden</option>
                    <option value="Switzerland">Switzerland</option>
                    <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                    <option value="Taiwan, Province of China">Taiwan, Province of China</option>
                    <option value="Tajikistan">Tajikistan</option>
                    <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                    <option value="Thailand">Thailand</option>
                    <option value="Timor-leste">Timor-leste</option>
                    <option value="Togo">Togo</option>
                    <option value="Tokelau">Tokelau</option>
                    <option value="Tonga">Tonga</option>
                    <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                    <option value="Tunisia">Tunisia</option>
                    <option value="Turkey">Turkey</option>
                    <option value="Turkmenistan">Turkmenistan</option>
                    <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                    <option value="Tuvalu">Tuvalu</option>
                    <option value="Uganda">Uganda</option>
                    <option value="Ukraine">Ukraine</option>
                    <option value="United Arab Emirates">United Arab Emirates</option>
                    <option value="United Kingdom">United Kingdom</option>
                    <option value="United States">United States</option>
                    <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                    <option value="Uruguay">Uruguay</option>
                    <option value="Uzbekistan">Uzbekistan</option>
                    <option value="Vanuatu">Vanuatu</option>
                    <option value="Venezuela">Venezuela</option>
                    <option value="Viet Nam">Viet Nam</option>
                    <option value="Virgin Islands, British">Virgin Islands, British</option>
                    <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
                    <option value="Wallis and Futuna">Wallis and Futuna</option>
                    <option value="Western Sahara">Western Sahara</option>
                    <option value="Yemen">Yemen</option>
                    <option value="Zambia">Zambia</option>
                    <option value="Zimbabwe">Zimbabwe</option>
                ';
            break;            
        }
        
        return $string;
    }
    
    
    /**
     * Pass sopranos order array to this function and count the quantity items in the array. Return the item count.
     *
     * @param array $aCartArray
     * 
     * @return int
     */
    function cartItemCount($aCartArray = array()) {

        $iCount = 0;

        if(!empty($aCartArray) && is_array($aCartArray)) {
            foreach($aCartArray as $key => $val) {
                if(isset($val['quantity'])) {
                    $iCount += $val['quantity'];
                }
            }
        }

        return $iCount;
    }


    /**
     * Update the items in size and quantity of a product in the shopping cart. Pass through the new updated id, else will not work.
     *
     * @param int $quantity
     * @param int $size
     * @param int $key
     * 
     * @return boolean
     */
    function cartUpdateItem($quantity, $size, $key) {

        if (!isset($quantity) && !isset($size) && !isset($key)) return false;

            $_SESSION['sopranos']['order'][$key]['quantity'] = $quantity;
            $_SESSION['sopranos']['order'][$key]['size_id'] = $size;

        return true;
    }


    /**
     * Pass in the sopranos order key to remove the item from cart. If successful then return true, else false.
     *
     * @param int $key
     * 
     * @return boolean
     */
    function cartRemoveItem($key = 0) {

        $iKey = json_decode($key);

            if (!is_int($iKey)) return false;

        return unsetSession($iKey, 'sopranos_order');
    }


    /**
     * Send a mail to the customer with their purchase information and stuff. Uses PHPMailer to send the mail.
     * 
     * @params array $CustomerArray
     * @params array $OrderArray
     * @params int $InvoiceNumber
     * @params int|null $CouponNumber
     *
     * @return mixed
     */
    function sendMail($CustomerArray = array(), $OrderArray = array(), $InvoiceNumber = 0, $CouponNumber = null) {

        if(empty($iShoppingCartCount)) {
            global $iShoppingCartCount;
        }


        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'username';
        $mail->Password = 'password';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;


        $sFullName = $CustomerArray['first_name'] .' '. $CustomerArray['last_name'];
        $sShippingInformation = $sFullName .'<br/>'. $CustomerArray['address'] .'<br/>'. $CustomerArray['zipcode'] .', '. $CustomerArray['city'] .'<br/>'. $CustomerArray['country'];
        $bEmailValid = isEmailValid($CustomerArray['email']);

        if (!$bEmailValid) return false;
        $sEmail = $CustomerArray['email'];
        

        $mail->setFrom('info@sopranos.com', 'Sopranos Pizzabar');
        $mail->addAddress($sEmail, $sFullName);

        $mail->isHTML(true);
        $mail->AddEmbeddedImage('assets/images/layout/sopranos-logo-footer.png', 'sopranos_logo');

        $mail->CharSet = "UTF-8";
        $mail->Subject = 'Thank you for your order at Sopranos Pizzabar with order no. '. $InvoiceNumber .' of '. $iShoppingCartCount .' item(s)'; 
        $mail->Body = createMailTemplate($OrderArray, $CouponNumber, $InvoiceNumber, $sFullName, $sShippingInformation);
        
        if (!$mail->send()) {
            return $mail->ErrorInfo;
        } else {
            return true;
        }
    }


    /**
     * Create a template to use for sending the invoice to the customer with PHPMailer. Uses order array to get most data and displays it nicely.
     * 
     * @params array $order
     * @params int|null $coupon
     * @params int $Ordernumber
     * @params string $CustomerName
     * @params string $ShippingAddress
     * 
     * @return string
     */
    function createMailTemplate($order = array(), $coupon = null, $Ordernumber = 0, $CustomerName = '', $ShippingAddress = '') {

        $s = '';
        
        $s .= '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Sopranos Pizzabar</title>
                <style>
                    *, 
                    :after, 
                    :before {
                        box-sizing: inherit;
                    }

                    html {
                        box-sizing: border-box;
                        font-size: 100%;
                    }

                    body {
                        font-size: 16px;
                        font-family: "Raleway", sans-serif;
                        line-height: 1.5;
                        color: #0a0918;
                    }
                         
                    .row {
                        max-width: 75rem;
                        margin-right: auto;
                        margin-left: auto;
                        display: flex;
                        flex-flow: row wrap;
                    }

                    .row .row {
                        margin-right: -.625rem;
                        margin-left: -.625rem;
                    }

                    .row.expanded, .row:not(.expanded) .row {
                        max-width: none;
                    }

                    .callout {
                        position: relative;
                        margin: 0 0 1rem;
                        padding: 1rem;
                        border: 1px solid hsla(0,0%,4%,.25);
                        border-radius: 0;
                        background-color: #fff;
                        color: #0a0a0a;
                    }

                    .callout.large {
                        padding: 3rem;
                    }                    

                    .column, 
                    .columns {
                        flex: 1 1 0px;
                        padding-right: .625rem;
                        padding-left: .625rem;
                        min-width: 0;
                    }

                    .align-left {
                        justify-content: flex-start;
                    }

                    .align-center {
                        justify-content: center;
                    }

                    .align-right {
                        justify-content: flex-end;
                    }

                    main {
                        margin-top: 4rem;
                        min-height: calc(100vh - 107px);
                    }
                  
                    main .inner-container {
                        max-width: 800px;
                        margin: 0 auto;
                    }

                    table {
                        width: 100%;
                        margin-bottom: 1rem;
                        border-radius: 0;
                    }

                    table tfoot td, table tfoot th, 
                    table thead td, table thead th {
                        padding: .5rem .625rem .625rem;
                        font-weight: 700;
                        text-align: left;
                    }

                    table tbody td, table tbody th {
                        padding: .5rem .625rem .625rem;
                    }
                  
                    table.invoice {
                        background: #fff;
                    }
                  
                    table.invoice .num {
                        font-weight: 200;
                        text-transform: uppercase;
                        letter-spacing: 1.5px;
                        font-size: .8em;
                    }
                  
                    table.invoice tr, table.invoice td {
                        background: #fff;
                        text-align: left;
                        font-weight: 400;
                        color: #322d28;
                    }
                  
                    table.invoice tr.header td img {
                        max-width: 300px;
                        display: block;
                        margin-bottom: 20px;
                    }
                  
                    table.invoice tr.header td h2 {
                        text-align: right;
                        font-family: "Montserrat", sans-serif;
                        font-weight: 200;
                        font-size: 2rem;
                        color: #1779ba;
                    }
                    
                    table.invoice tr.intro td:nth-child(2) {
                        text-align: right;
                    }
                    
                    table.invoice tr.details > td {
                        padding-top: 4rem;
                        padding-bottom: 0;
                    }
                    
                    table.invoice tr.details td.id, table.invoice tr.details td.topping ,table.invoice tr.details td.qty, table.invoice tr.details th.id, table.invoice tr.details th.qty, table.invoice tr.details th.size, table.invoice tr.details td.size {
                        text-align: center;
                    }

                    table.invoice tr.details td.topping {
                        width: 100%;
                        text-overflow: ellipsis;
                        white-space: nowrap;
                        overflow: hidden;
                        max-width: 200px;
                    }

                    table.invoice tr.details td.topping span:not(:last-of-type):after {
                        content: "•";
                        padding: 0 0.25rem;
                    }
                    
                    table.invoice tr.details td:last-child, table.invoice tr.details th:last-child {
                        text-align: right;
                    }
                    
                    table.invoice tr.details table thead, table.invoice tr.details table tbody {
                        position: relative;
                    }
                    
                    table.invoice tr.details table thead:after, table.invoice tr.details table tbody:after {
                        content: "";
                        height: 1px;
                        position: absolute;
                        width: 100%;
                        left: 0;
                        margin-top: -1px;
                        background: #c8c3be;
                    }
                    
                    table.invoice tr.totals td {
                        padding-top: 0;
                    }
                    
                    table.invoice tr.totals table tr td {
                        padding-top: 0;
                        padding-bottom: 0;
                    }
                    
                    table.invoice tr.totals table tr td:nth-child(1) {
                        font-weight: 500;
                    }
                    
                    table.invoice tr.totals table tr td:nth-child(2) {
                        text-align: right;
                        font-weight: 200;
                    }
                    
                    table.invoice tr.totals table tr:nth-last-child(2) td {
                        padding-bottom: .5em;
                    }
                    
                    table.invoice tr.totals table tr:nth-last-child(2) td:last-child {
                        position: relative;
                    }
                    
                    table.invoice tr.totals table tr:nth-last-child(2) td:last-child:after {
                        content: "";
                        height: 4px;
                        width: 110%;
                        border-bottom: 1px solid #f58220;
                        position: relative;
                        right: 0;
                        bottom: -.575rem;
                        display: block;
                    }
                    
                    table.invoice tr.totals table tr.total td {
                        font-size: 1.2em;
                        padding-top: .5em;
                        font-weight: 700;
                    }
                    
                    table.invoice tr.totals table tr.total td:last-child {
                        font-weight: 700;
                    }
                    
                    .additional-info h5 {
                        font-size: .8em;
                        font-weight: 700;
                        text-transform: uppercase;
                        letter-spacing: 2px;
                        color: #f58220;
                    }
                </style>
            </head>
            <body>
            
            <div class="row expanded">
                <main class="columns">
                    <div class="inner-container">

                        <section class="row">
                            <div class="callout large invoice-container">

                                <table class="invoice">

                                    <tr class="header">
                                        <td><img src="cid:sopranos_logo" alt="Sopranos Pizzabar" /></td>
                                    </tr>

                                    <tr class="intro">
                                        <td>Hello, '. $CustomerName .'.<br>Thank you for your order.</td>
                                        <td class="text-right"><span class="num">Order no. '. $Ordernumber .'</span><br>'. date("F j, Y") .'</td>
                                    </tr>

                                    <tr class="details">
                                        <td colspan="2">
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th class="desc">Item</th>
                                                        <th class="id">Topping(s)</th>
                                                        <th class="size">Size</th>
                                                        <th class="qty">Quantity</th>
                                                        <th class="amt">Subtotal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>';

                                                    $iTotalPrice = 0.00;
                                                    $iSubtotalPrice = 0.00;

                                                    if(!empty($order) && is_array(($order))) {
                                                        foreach ($order as $item) {

                                                            $iSubtotal = 0.00;

                                                            $aPizzaType = selectAllById('pizzas_type', $item['type_id']);
                                                            $aPizzaSize = selectAllById('pizzas_size', $item['size_id']);

                                                            $iSubtotal += $aPizzaType['price'] * $item['quantity'];
                                                            $iSubtotal += $aPizzaSize['price'] * $item['quantity'];

                                                            $s .= '
                                                                <tr class="item">
                                                                    <td class="desc">'. $aPizzaType['name'] .'</td>
                                                                    <td class="topping">';

                                                                    if(!empty($item['topping_id']) && is_array($item['topping_id'])) {
                                                                        foreach($item['topping_id'] as $iToppingId => $sToppingName) {
                                                                            $aPizzaTopping = selectAllById('pizzas_topping', $iToppingId);

                                                                            $iSubtotal += $aPizzaTopping['price'] * $item['quantity'];

                                                                            $s .= '<span class"label">'.$sToppingName .'</span>';
                                                                        }
                                                                    } else {
                                                                        $s .= '-';
                                                                    }

                                                                    $s .= '</td>
                                                                    <td class="size">'. $aPizzaSize['size'] .'</td>
                                                                    <td class="qty">'. $item['quantity'] .'x</td>
                                                                    <td class="amt">€'. number_format((float)$iSubtotal, 2, '.', '') .'</td>
                                                                </tr>    
                                                            ';

                                                            $iSubtotalPrice += $iSubtotal;
                                                            $iTotalPrice += $iSubtotal;
                                                        }
                                                    }

                                                    
                                            $s .= '</tbody>
                                            </table>
                                        </td> 
                                    </tr>

                                    <tr class="totals">
                                        <td></td>
                                        <td>
                                            <table>
                                                <tr class="subtotal">
                                                    <td class="num">Subtotal</td>
                                                    <td class="num">€'. number_format((float)$iSubtotalPrice, 2, '.', '') .'</td>
                                                </tr>
                                                <tr class="fees">
                                                    <td class="num">Shipping</td>
                                                    <td class="num">€0.00</td>
                                                </tr>';

                                                if(!empty($coupon) && !is_null($coupon)) {

                                                    $iDiscountPrice = 0.00;
                                                    $aCoupon = selectAllById('coupons', $coupon);

                                                    switch ($aCoupon['type']) {
                                                        case 1: # percentage
                                                            $iDiscountPrice = $iTotalPrice * ($aCoupon['discount'] / 100);                        
                                                        break;
                                                        case 2: # money
                                                            $iDiscountPrice = $aCoupon['discount'];
                                                        break;
                                                    }

                                                    $s .= '
                                                        <tr class="tax">
                                                            <td class="num">Discount ('. $aCoupon['discount'] .'%)</td>
                                                            <td class="num">- €'. number_format((float)$iDiscountPrice, 2, '.', '') .'</td>
                                                        </tr>
                                                    ';

                                                    $iTotalPrice -= $iDiscountPrice;
                                                }

                                                $s .= '<tr class="total">
                                                    <td>Total</td>
                                                    <td>€'. number_format((float)$iTotalPrice, 2, '.', '') .'</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                  
                                <section class="additional-info">
                                    <div class="row">
                                        <div class="columns">
                                            <h5>Billing Information</h5>
                                            <p>'.$ShippingAddress.'</p>
                                        </div>
                                    </div>
                                </section>

                            </div>
                        </section>

                    </div>
                </main>
            </div>

            </body>
            </html>
        ';

        return $s;
    }

    
    if(!isset($_SESSION['sopranos']['number'])) { saveInSession('number', generateUniqueId()); }

    $aTypePizzas = selectAllById('pizzas_type');
    $aSizePizzas = selectAllById('pizzas_size');
    $aToppingPizzas = selectAllById('pizzas_topping');

    $aSopranosBranches = selectAllById('branches', 5);

    $iShoppingCartCount = isset($_SESSION['sopranos']['order']) ? cartItemCount($_SESSION['sopranos']['order']) : 0;
?>