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
     * @return void
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
    function getFiles($dir = '', $ext = '') {

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

        $coupons = $pdo->query("SELECT * FROM coupons WHERE 1 AND valid <= '".$datetime."' AND expire >= '".$datetime."' AND quantity > 0  AND status = 1 ORDER BY id DESC");

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
    function queryOperator($sql = '', $join = '', $where = '', $order = '', $limit = 0) {

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
     * @params float $Totalprice
     *
     * @return mixed
     */
    function sendMail($CustomerArray = array(), $OrderArray = array(), $InvoiceNumber = 0, $CouponNumber = null, $Totalprice = 0.00) {

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
        $bEmailValid = isEmailValid($CustomerArray['email']);

        if (!$bEmailValid) return false;
        $sEmail = $CustomerArray['email'];
        

        $mail->setFrom('info@sopranos.com', 'Sopranos Pizzabar');
        $mail->addAddress($sEmail, $sFullName);

        $mail->isHTML(true);
        $mail->AddEmbeddedImage('assets/images/layout/sopranos-logo-footer.png', 'sopranos_logo');

        $mail->CharSet = "UTF-8";
        $mail->Subject = 'Thank you for your order at Sopranos Pizzabar with order no. '. $InvoiceNumber .' of '. $iShoppingCartCount .' item(s)'; 
        $mail->Body = '<img src="cid:sopranos_logo"><h1>Hello, '. $sFullName .'.<br/>Thank you for your purchase(s) at Sopranos Pizzabar!</h1><h2>Total: €'. number_format((float)$Totalprice, 2, '.', '') .' EUR</h2>';
        
        if (!$mail->send()) {
            return $mail->ErrorInfo;
        } else {
            return true;
        }
    }


    /**
     * Verifies the user attempt to login. Check if certain parameters match and return true if this is the case. Else it is false. Or return the error message.
     * 
     * @params string $info
     * 
     * @return mixed
     */
    function verifyLogin($info = '') {

        if (empty($pdo)) {
            global $pdo;
        }

        $params = array();
        parse_str($info, $params);

        $incorrect = array();

        if (!empty($params) && isset($params)) {

            if (empty($params['email'])) {
                $error['email'] = 'Fill in your email address.';
            }

            if (empty($params['password'])) {
                $error['password'] = 'Fill in your password.';
            }
        }


        if (isset($error) && $error !== '') {
            return $error;
        }


        $sql = "SELECT * FROM accounts WHERE 1 AND email = :email LIMIT 1";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':email', $params['email']);
        $stmt->execute();

        $aAccounts = $stmt->fetch(PDO::FETCH_ASSOC);

        
        if ($stmt->rowCount() > 0 ) {

            if (password_verify($params['password'], $aAccounts['password'])) {

                $bLastLogin = $pdo->prepare('UPDATE accounts SET last_login = :last_login WHERE 1 AND id = :id LIMIT 1')->execute(array(':last_login' => date("YmdHis"), ':id' => $aAccounts['id']));

                    if (!$bLastLogin) return false;

                if (isset($params['cookie']) && $params['cookie'] !== '') {

                    return setcookie('uid', $aAccounts['id'], time() + 86400, "/");

                } else {

                    $_SESSION['profile']['uid'] = $aAccounts['id']; return true;

                }

            } else {

                $incorrect['password'] = 'Incorrect password';
            }

        } else {

            $incorrect['email'] = 'Invalid email address';
        }

        return $incorrect;
    }


    /**
     * Create a new account for those who don't have an account. They will need to provide a valid email address, name and password. Optional is their phone number. Make sure to clean the user input.
     * 
     * @params string $data
     * 
     * @return mixed
     */
    function createNewAccount($data = '') {

        if (empty($pdo)) {
            global $pdo;
        }


        $params = array();
        parse_str($data, $params);

        $errors = array();

        if (!empty($params) && isset($params)) {

            if (empty($params['name'])) {
                $errors['name'] = 'Fill in your full name.';
            }

            if (empty($params['email'])) {
                $errors['email'] = 'Fill in your email address.';
            }

            if (empty($params['password'])) {
                $errors['password'] = 'Enter your desired password.';
            }

            if (empty($params['password_confirmation'])) {
                $errors['password_confirmation'] = 'Enter your password again.';
            }
        }

        if (isset($errors) && count($errors) > 0) {
            return $errors;
        }

        $bValidEmail = isEmailValid($params['email']);

        if (!$bValidEmail) {
            $errors['email'] = 'Email is not valid.'; return $errors;
        }


        if ($params['password'] !== $params['password_confirmation'] && $params['password_confirmation'] !== $params['password']) {
            $errors['password_confirmation'] = 'Password confirmation doesn\'t match Password.'; return $errors;
        }


        $aAccountExist = $pdo->prepare("SELECT * FROM accounts WHERE email = :email LIMIT 1");

        $aAccountExist->bindParam(':email', $params['email']);
        $aAccountExist->execute();

        $aAccountExist->fetch(PDO::FETCH_ASSOC);

        if ($aAccountExist->rowCount() > 0) {
            $errors['email'] = 'Email is already in use.'; return $errors;
        }


        if (count($errors) === 0 && !empty($params)) {

            $sSql = "
                INSERT INTO accounts
                SET 
                    image_id = null, 
                    fullname = :name,
                    password = :password,
                    email = :email,
                    phone = 0,
                    admin = 0,
                    account_created = :today, 
                    last_login = 0
            ";

            $aInsertSql = $pdo->prepare($sSql);

            $aInsertSql->bindValue(':name', $params['name']);
            $aInsertSql->bindValue(':password', password_hash($params['password'], PASSWORD_DEFAULT));
            $aInsertSql->bindValue(':email', $params['email']);
            $aInsertSql->bindValue(':today', date("YmdHis"));

            $aInsertSql->execute();

            if(!$aInsertSql) {
                return false;
            }
        }

        return true;
    }


    /**
     * Redirect page to desired input.
     * 
     * @params string $page
     * 
     * @return void|boolean
     */
    function redirectPage($page = '') {

        if (empty($page)) return false;

        header("Location: $page");
        exit();
    }


    /**
     * Logout current account from dashboard
     * 
     * @return void
     */
    function accountLogout() {

        $bCookieOut = setcookie('uid', '', 1, '/');
        $bLoggedOut = clearSession();

        if ($bLoggedOut && $bCookieOut) {
            return redirectPage('login.php');
        }
    }


    /**
     * If the user is not logged in, send error message and redirect back to the login page.
     * 
     * @return void
     */
    function sendLoginError() {

        return redirectPage('login.php');
    }


    /**
     * Generate an initial avatar, based on their full name.
     * 
     * @params string $name
     * 
     * @return string
     */
    function getNameInitials($name = '') {

        preg_match_all('#(?<=\s|\b)\pL#u', $name, $res);
        $initials = implode('', $res[0]);
    
        if (strlen($initials) < 2) {
            $initials = strtoupper(substr($name, 0, 2));
        } else if (strlen($initials) > 2) {
            $initials = substr($initials, 0, 2);
        }

        return strtoupper($initials);
    }
    
    
    /**
     * Update accounts table with the data from dashboard. Only the fullname and phone number.
     * 
     * @params array $info
     * 
     * @return mixed
     */
    function updateAccountInformation($info = array()) {

        if(empty($pdo)) {
            global $pdo;
        }

        $errors = array();

        if (empty($info)) {
            $errors['feedback'] = 'Could not update your account information.'; return $errors;
        }


        if (count($errors) === 0) {
            $sSql = "
                UPDATE accounts 
                SET
                    fullname = :name,
                    phone = :phone
                    WHERE 1
                    AND id = :id
                    LIMIT 1
            ";

            $aUpdateSql = $pdo->prepare($sSql);
                        
            if (!empty($info['user']) && is_array($info['user'])) {
                foreach($info['user'] as $key => &$val) {
                    $aUpdateSql->bindParam($key, $val);
                }
            }
                
            $aUpdateSql->bindParam(':id', $info['token']);
            $aUpdateSql->execute();
        }
        
        return flashMessage('settings', 'Successfully updated your Account Information.', 'dashboard__form_message dashboard__form_message--success');
    }


    /**
     * Update password. Checks if the requirements first got met before updating.
     * 
     * @params array $data
     * 
     * @return mixed
     */
    function updateAccountPassword($data = array()) {

        if(empty($pdo)) {
            global $pdo;
        }

        $errors = array();

        if ($data['token'] <= 0) {
            $errors['feedback'] = 'Something went wrong, please reload your browser.';

            return $errors;
        }


        if (!empty($data)) {

            if (empty($data['password']['current']) || empty($data['password']['new']) || empty($data['password']['confirm'])) {
                $errors['feedback'] = 'Passwords textfields can\'t be empty.';

                return $errors;
            }

            if (strlen($data['password']['new']) < 6) {
                $errors['feedback'] = 'Your password isnt\'t longer than 6 characters.'; return $errors;
            } 

            $sSelectPassword = "
                SELECT password FROM accounts
                    WHERE 1
                    AND id = :id
                    LIMIT 1
            ";

            $aSelectSql = $pdo->prepare($sSelectPassword);

            $aSelectSql->bindParam(':id', $data['token']);
            $aSelectSql->execute();

            $aPassword = $aSelectSql->fetch(PDO::FETCH_ASSOC);

            if ($aSelectSql->rowCount() > 0 ) {

                if (!empty($data['password']['current']) && !empty($data['password']['new']) && !empty($data['password']['confirm'])) {

                    if (password_verify($data['password']['current'], $aPassword['password'])) {

                        if ($data['password']['new'] === $data['password']['confirm']) {

                            $password = $data['password']['new'];

                        } else {
                            $errors['feedback'] = 'New password doesn\'t match the confirmation one.';
                        }

                    } else {
                        $errors['feedback'] = 'Your current password does not match with the given password.';
                    }
                }
            } else {
                $errors['feedback'] = 'Your account doesn\'t exist.';
            } 


            if (count($errors) > 0) {
                return $errors;
            }

            if (count($errors) === 0) {

                $sSql = "
                    UPDATE accounts 
                    SET
                        password = :password
                        WHERE 1
                        AND id = :id
                        LIMIT 1
                ";

                $aUpdateSql = $pdo->prepare($sSql);

                $aUpdateSql->bindValue(':password', password_hash($password, PASSWORD_DEFAULT));
                $aUpdateSql->bindValue(':id', $data['token']);
                $aUpdateSql->execute();
            }
        }

        return flashMessage('settings', 'Successfully updated your Password.', 'dashboard__form_message dashboard__form_message--success');
    }


    /**
     * Update email, they will need to confirm their current password before the update is getting executed.
     * 
     * @params array $account
     * 
     * @return mixed
     */
    function updateAccountEmail($account = array()) {

        if(empty($pdo)) {
            global $pdo;
        }

        $errors = array();

        if (empty($account['email']['new']) || empty($account['email']['confirm']) || empty($account['email']['password'])) {
            $errors['feedback'] = 'Some textfields can\'t be empty.'; return $errors;
        }

        $aSelectPassword = queryOperator("SELECT password FROM accounts", "", "id = '". $account['token'] ."'", "", 1);

        if (!empty($aSelectPassword)) {

            if (password_verify($account['email']['password'], $aSelectPassword['password'])) {

                if ($account['email']['new'] === $account['email']['confirm']) {
    
                    $aDuplicateEmail = queryOperator("SELECT email FROM accounts", "", "email = '". $account['email']['new'] ."'", '', 1);

                    if (!empty($aDuplicateEmail)) {
                        $errors['feedback'] = 'Your chosen email address is unavailable.';                    
                    }
    
                } else {
                    $errors['feedback'] = 'New email address doesn\'t match the confirmation one.';
                }
            } else {
                $errors['feedback'] = 'Your current password does not match with the given password.';
            }

        } else {
            $errors['feedback'] = 'Could not retreive your current password assigned with your account.';
        }


        if (count($errors) > 0) {
            return $errors;
        }

        if (count($errors) === 0) {

            $sSql = "
                UPDATE accounts
                SET
                    email = :email
                    WHERE 1
                    AND id = :id
                    LIMIT 1
            ";

            $aUpdateSql = $pdo->prepare($sSql);

            $aUpdateSql->bindParam(':email', $account['email']['new']);
            $aUpdateSql->bindParam(':id', $account['token']);
            $aUpdateSql->execute();
        }

        return flashMessage('settings', 'Successfully updated your Email Address.', 'dashboard__form_message dashboard__form_message--success');
    }


    /**
     * Function to create and display error and success messages
     * 
     * @params string $name
     * @params string $message
     * @params string $class
     * 
     * @return void
     */
    function flashMessage($name = '', $message = '', $class = 'success') {
    
        if (!empty($name)) {

            if (!empty($message) && empty($_SESSION[$name])) {

                if (!empty($_SESSION[$name])) {
                    unset($_SESSION[$name]);
                }
                        
                if (!empty($_SESSION[$name.'_class'])) {
                    unset($_SESSION[$name.'_class']);
                }

                $_SESSION[$name] = $message;
                $_SESSION[$name.'_class'] = $class;

            } elseif (!empty($_SESSION[$name]) && empty($message)) {
                $class = !empty($_SESSION[$name.'_class']) ? $_SESSION[$name.'_class'] : 'success';
                echo '<div class="'.$class.'">'.$_SESSION[$name].'</div>';
                unset($_SESSION[$name]);
                unset($_SESSION[$name.'_class']);
            }
        }
    }


    /**
     * Create new coupon code to use. Make sure certain fields are not empty. Also an expire date and valid date (when you can use them).
     * 
     * @params array $coupons
     * 
     * @return mixed
     */
    function createNewCoupons($coupons = array()) {

        if (empty($pdo)) {
            global $pdo;
        }

        $errors = array();

        if (empty($coupons['coupon']['code']) || empty($coupons['coupon']['discount']) || empty($coupons['coupon']['type']) || empty($coupons['coupon']['quantity']) || empty($coupons['coupon']['status']) || empty($coupons['coupon']['valid']) || empty($coupons['coupon']['expire'])) {
            $errors['feedback'] = 'Some textfields can\'t be empty.'; return $errors;
        }


        if (count($errors) === 0) {

            $sSql = "
                INSERT INTO coupons
                SET
                    code = :code,
                    discount = :discount,
                    type = :type,
                    quantity = :quantity,
                    status = :status,
                    valid = :valid,
                    expire = :expire
            ";

            $aInsertSql = $pdo->prepare($sSql);

            $aInsertSql->bindValue(':code', $coupons['coupon']['code']);
            $aInsertSql->bindValue(':discount', $coupons['coupon']['discount']); 
            $aInsertSql->bindValue(':type', $coupons['coupon']['type']);
            $aInsertSql->bindValue(':quantity', $coupons['coupon']['quantity']);
            $aInsertSql->bindValue(':status', $coupons['coupon']['status']);
            $aInsertSql->bindValue(':valid', $coupons['coupon']['valid']);
            $aInsertSql->bindValue(':expire', date("YmdHis", strtotime($coupons['coupon']['expire'])));
            $aInsertSql->execute();
        }

        return flashMessage('coupons', 'Successfully created a new Coupon.', 'dashboard__form_message dashboard__form_message--success');
    }


    /**
     * Delete coupon code with AJAX request. Returns boolean on success.
     * 
     * @params int $id
     * 
     * @return boolean
     */
    function removeCouponCode($id = 0) {

        if (empty($pdo)) {
            global $pdo;
        }

            if (empty($id) && !is_int($id)) return false;

        $checkCoupon = queryOperator("SELECT * FROM orders", "", "coupon_id = '". $id ."'");

        if (!empty($checkCoupon) && $checkCoupon > 0) {
            $pdo->prepare("UPDATE orders SET coupon_id = NULL WHERE 1 AND coupon_id = :coupon_id")->execute(array(':coupon_id' => $id));
        }

        $stmt = $pdo->prepare("DELETE FROM coupons WHERE 1 AND id = :id LIMIT 1"); 
        $stmt->bindParam(':id', $id);
        $stmt->execute();

            if (!$stmt) {
                return false;
            }

        return true;
    }


    /**
     * Make a branch the main location / primairy shop. Email will be sent from the main branch as well.
     * 
     * @params int $id
     * 
     * @return boolean
     */
    function switchFavoriteBranch($id = 0) {

        if (empty($pdo)) {
            global $pdo;
        }

            if (empty($id) && !is_int($id)) return false;

        $bReset = $pdo->query("UPDATE branches SET status = 0");

        if (!$bReset) {
            return false;
        }

        $stmt = $pdo->prepare("UPDATE branches SET status = 1 WHERE 1 AND id = :id LIMIT 1");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

            if (!$stmt) {
                return false;
            }

        return true;
    }


    /**
     * Create new items for the shop, switch case to determine which item needs to be created and return appropriate feedback.
     * 
     * @params array $post
     * 
     * @return mixed
     */
    function storesCreateNewItem($post = array()) {

        if (empty($pdo)) {
            global $pdo;
        }

        $errors = array();

        if (empty($post['table'])) {
            $errors['feedback'] = 'Something went wrong, please try again.'; return $errors;
        }


        if (count($errors) === 0) {

            $sInsertSql = "INSERT INTO ". $post['table'] ." SET ";

            if(!empty($post['item'])) {
                foreach($post['item'] as $key => &$val) {
                    $sInsertSql .= "$key = '$val', ";
                }
            }

            $sInsertSql .= "status = 1";

            $pdo->query($sInsertSql);
        }

        return flashMessage('items', 'Successfully create a new Shop Item.', 'dashboard__form_message dashboard__form_message--success');
    }


    /**
     * Delete stores item with the assigned id and in the right table. Functions for type, size and toppings table.
     * 
     * @params string $table
     * @params int $id
     * 
     * @return boolean
     */
    function storesDeleteItem($table = '', $id = 0) {

        if (empty($pdo)) {
            global $pdo;
        }

            if (!is_string($table) && !is_int($id)) return false;

        $sSql = "DELETE FROM $table WHERE 1 AND id = $id LIMIT 1";
        $bDeleteItem = $pdo->query($sSql);

        if (!$bDeleteItem) {
            return false;
        }

        return true;
    }


    /**
     * Update the items, such as name, quantity, price, status and more. Make sure there is an id given before this functions.
     * 
     * @params array $items
     * 
     * @return mixed
     */
    function storesItemUpdate($items = array()) {

        if (empty($pdo)) {
            global $pdo;
        }

        $errors = array();

        if (empty($items['module'])) {
            $errors['feedback'] = 'Module is missing, please reload your browser.'; return $errors;
        }

        if (empty($items['key']) || $items['key'] <= 0 || !isset($items['module'])) {
            $errors['feedback'] = 'Something went wrong, please try again.'; return $errors;
        }


        if (count($errors) === 0) {

            switch ($items['module']) {
                case 'pizzas_type':
                case 'pizzas_topping':
                    $sSql = "
                        UPDATE ". $items['module'] ."
                        SET
                            name = :name,
                            quantity = :quantity,
                            price = :price,
                            status = :status
                            WHERE 1
                            AND id = :id
                            LIMIT 1
                    "; 
                break;
                case 'pizzas_size':
                    $sSql = "
                        UPDATE ". $items['module'] ."
                        SET
                            name = :name,
                            size = :size,
                            price = :price,
                            status = :status
                            WHERE 1
                            AND id = :id
                            LIMIT 1
                    "; 
                break;
            }


            $aUpdateSql = $pdo->prepare($sSql);

            if(!empty($items['stores'])) {
                foreach($items['stores'] as $key => &$val) {
                    $aUpdateSql->bindParam($key, $val);
                }
            }

            $aUpdateSql->bindParam(':id', $items['key']);
            $aUpdateSql->execute();
        }

        return flashMessage('items', 'Successfully updated the Shop Item.', 'dashboard__form_message dashboard__form_message--success');
    }

    

    if(!isset($_SESSION['sopranos']['number'])) { saveInSession('number', generateUniqueId()); }

    $aSopranosBranches = queryOperator("SELECT * FROM branches", "", "status = 1", "id DESC", 1);
    $aSopranosTypes = queryOperator("SELECT * FROM pizzas_type", "", "status = 1 AND quantity > 0");
    $aSopranosSizes = queryOperator("SELECT * FROM pizzas_size", "", "status = 1");
    $aSopranosToppings = queryOperator("SELECT * FROM pizzas_topping", "", "status = 1 AND quantity > 0");


    $Branches = queryOperator("SELECT * FROM branches", "", "id != '". $aSopranosBranches['id']. "'");
    $Coupons = queryOperator("SELECT * FROM coupons");
    $Customers = queryOperator("SELECT * FROM customers");

    $Types = queryOperator("SELECT * FROM pizzas_type");
    $Sizes = queryOperator("SELECT * FROM pizzas_size");
    $Toppings = queryOperator("SELECT * FROM pizzas_topping");

    $Status = [["status" => "Active", "key" => "1"], ["status" => "Inactive", "key" => "0"]];
    
    $iShoppingCartCount = isset($_SESSION['sopranos']['order']) ? cartItemCount($_SESSION['sopranos']['order']) : 0;
?>