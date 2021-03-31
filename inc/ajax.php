<?php
    /* Copyright (c) - 2021 by Junyi Xie */

    include_once("../inc/connect.php");
    include_once('../inc/functions.php');

    if(isset($_POST['action']) && !empty($_POST['action'])) {

        $date = date("YmdHis");
        $action = $_POST['action'];

        switch($action) {
            case 'validate_email': 
                $bEmail = isEmailValid($_POST['email']);
                echo json_encode($bEmail);
            break;
            case 'apply_coupon':
                $aValidCoupons = selectValidCoupons($date);
                $iCoupon = validateCouponCode($aValidCoupons, $_POST['code']);
                echo json_encode($iCoupon);
            break;
            case 'get_coupon':
                $aCouponData = selectAllById('coupons', $_POST['coupon_id']);
                echo json_encode($aCouponData);
            break;
            case 'update_order_item':
                $bItemUpdated = cartUpdateItem($_POST['quantity'], $_POST['size'], $_POST['key']);
                echo json_encode($bItemUpdated);
            break;
            case 'remove_order_item':
                $bItemRemoved = cartRemoveItem($_POST['key']);
                echo json_encode($bItemRemoved);
            break;
            case 'save_customer_order':
                $bCustomerOrder = saveCustomerOrder($_POST['cart']);
                echo json_encode($bCustomerOrder);
            break;
            case 'save_customer_information':
                $bCustomerInformation = saveCustomerData($_POST['data']);
                echo json_encode($bCustomerInformation);
            break;
            case 'login_verify_account':
                $aLoginOutput = verifyLogin($_POST['info']);
                echo json_encode($aLoginOutput);
            break;
            case 'create_new_account_signup':
                $bNewAccount = createNewAccount($_POST['signup']);
                echo json_encode($bNewAccount);
            break;
        }
    }    
?>