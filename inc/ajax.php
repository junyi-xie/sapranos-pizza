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
            case 'update_general_info':
                $aAccountInformation = updateAccountInformation($_POST);
                (isset($aAccountInformation['feedback']) ? flashMessage('settings', $aAccountInformation['feedback'], 'dashboard__form_message dashboard__form_message--alert') : '');
            break;
            case 'update_email_address':
                $aUpdatedEmail = updateAccountEmail($_POST);
                (isset($aUpdatedEmail['feedback']) ? flashMessage('settings', $aUpdatedEmail['feedback'], 'dashboard__form_message dashboard__form_message--alert') : '');
            break;
            case 'update_password':
                $aPasswordUpdate = updateAccountPassword($_POST);
                (isset($aPasswordUpdate['feedback']) ? flashMessage('settings', $aPasswordUpdate['feedback'], 'dashboard__form_message dashboard__form_message--alert') : '');
            break;
            case 'remove_coupon_delete':
                $bDeletedCouponCode = removeCouponCode($_POST['coupon_id']);
                echo json_encode($bDeletedCouponCode);
            break;
            case 'coupon_code_create':
                $aNewCouponsAdded = createNewCoupons($_POST);
                (isset($aNewCouponsAdded['feedback']) ? flashMessage('coupons', $aNewCouponsAdded['feedback'], 'dashboard__form_message dashboard__form_message--alert') : '');
            break;
            case 'change_favorite_branches':
                $bMainBranch = switchFavoriteBranch($_POST['key']);
                echo json_encode($bMainBranch);
            break;
            case 'stores_create_new_item':
                $aNewItemCreated = storesCreateNewItem($_POST);
                (isset($aNewItemCreated['feedback']) ? flashMessage('items', $aNewItemCreated['feedback'], 'dashboard__form_message dashboard__form_message--alert') : '');
            break;
            case 'stores_delete_item':
                $bStoresRemoveItem = storesDeleteItem($_POST['module'], $_POST['key']);
                echo json_encode($bStoresRemoveItem);
            break;
            case 'stores_edit_valid_item':
                $bUpdatedStoresItem = storesItemUpdate($_POST);
                (isset($bUpdatedStoresItem['feedback']) ? flashMessage('items', $bUpdatedStoresItem['feedback'], 'dashboard__form_message dashboard__form_message--alert') : '');
            break;
        }

        if (isset($_POST['url']) || isset($_POST['link'])) {
            redirectPage((!isset($_POST['url']) ? $_POST['link'] : $_POST['url']));
            exit;
        }
    }    
?>