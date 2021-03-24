<?php
    /* Copyright (c) - 2021 by Junyi Xie */	

    include_once("inc/connect.php");
    include_once("inc/functions.php");
    include_once("inc/class.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Checkout · Sopranos Pizzabar</title>
    <meta charset="UTF-8">
    <meta name="author" content="Junyi Xie">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/images/favicon/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css?<?php echo date("YmdHis"); ?>" media="screen">
</head>
<body class="js-body">

<?php include_once("inc/header.php") ?>

<?php if(!empty($_SESSION['sopranos'] && !empty($_SESSION['sopranos']['number']) && !empty($_SESSION['sopranos']['order']) && !empty($_SESSION['sopranos']['customer'])) && !empty($_GET['go']) && $_GET['go'] == 'checkout'): ?>

<?php $SopranosOrders = new Sopranos\Orders($_SESSION['sopranos'], $pdo); ?>

<?php sendMail($SopranosOrders->getCustomer(), $SopranosOrders->getOrder(), $SopranosOrders->getNumber(), $SopranosOrders->getCoupon(), $SopranosOrders->getPrice()); ?>

<div class="checkout_order__container">

    <div class="checkout_order--wrapper">

        <div class="checkout_order__edit site__wrapper">

            <div class="checkout_order__header">

                <h1 class="checkout_order__title">Thank you.</h1>

                <p class="checkout_order__subtitle">Your order was completed successfully.</p>

            </div>

            <div class="checkout_order__mail--details">

                <div class="checkout_order__mail--image">
                
                    <img class="checkout_order__mail--thumb" src="assets/images/layout/mail.png">
                
                </div>

                <p class="checkout_order__mail--text">An email receipt including the details about your order has been sent to the email address provided. Please keep it for your records.</p>

            </div>

            <div class="checkout_order__shop_again">

                <p class="checkout_order__shop_again-text">If you wish to continue shopping, click here to go direct to the <a class="checkout_order__shop_again--link-alt" href="shop.php">shopping page.</a></p>

            </div>

        </div>

    </div>

</div>

<?php elseif (!empty($_SESSION['sopranos']['order']) && count($_SESSION['sopranos']['order']) > 0): ?>

<div class="site__content_container">

    <div class="site__main">

        <div class="site__wrapper">

            <div class="checkout__wrapper">
            
                <form class="order_form_container js-order_form_container" action="checkout.php?go=checkout" accept-charset="UTF-8" method="post" id="order_form">

                    <div class="checkout__container">
                    
                        <div class="checkout_form">

                            <div class="checkout_form__header">
                        
                                <h2 class="checkout_form__title">Checkout</h2>

                            </div>

                            <fieldset>
                    
                                <div class="form__group">
                                    
                                    <legend class="checkout__legend">Contact info</legend>

                                    <input class="form__textfield form__textfield--full js-checkout-email js-checkout-form-required" type="email" name="customer[email]" placeholder="Email address" id="order_form_email" required>

                                    <div class="form__error js-form-error--checkout-email hidden">* Enter a valid email address</div>

                                    <input class="form__textfield form__textfield--full js-checkout-phone js-checkout-form-required" type="tel" name="customer[phone]" placeholder="Phone number" id="order_form_phone" required>

                                    <div class="form__error js-form-error--checkout-phone hidden">* Enter a valid phone number</div>

                                </div>

                            </fieldset>

                            <fieldset>

                                <div class="form__group">

                                    <legend class="checkout__legend">Shipping info</legend>

                                    <div class="checkout_form__row">

                                        <div class="checkout__input--half">

                                            <input class="form__textfield form__textfield--full js-shipping-first-name js-checkout-form-required" type="text" name="customer[first_name]" placeholder="First name" id="order_form_first_name" required>

                                            <div class="form__error js-form-error--first-name hidden">* Enter your first name</div>

                                        </div>

                                        <div class="checkout__input--half">

                                            <input class="form__textfield form__textfield--full js-shipping-last-name js-checkout-form-required" type="text" name="customer[last_name]" placeholder="Last name" id="order_form_last_name" required>

                                            <div class="form__error js-form-error--last-name hidden">* Enter your last name</div>

                                        </div>

                                    </div>
                                        
                                    <input class="form__textfield form__textfield--full js-shipping-address js-checkout-form-required" type="text" name="customer[address]" placeholder="Street address" id="order_form_address" required>

                                    <div class="form__error js-form-error--shipping-address hidden">* Enter a valid address</div>

                                    <input class="form__textfield form__textfield--full" type="text" name="customer[address_2]" placeholder="Apt / Suite / Other (optional)" id="order_form_address_2">

                                    <div class="checkout_form__row">

                                        <div class="checkout__input--half">

                                            <input class="form__textfield form__textfield--full js-shipping-city js-checkout-form-required" type="text" name="customer[city]" placeholder="City" id="order_form_city" required>

                                            <div class="form__error js-form-error--shipping-city hidden">* Enter a valid city</div>
                                        
                                        </div>

                                        <div class="checkout__input--half">

                                            <input class="form__textfield form__textfield--full js-shipping-province js-checkout-form-required" type="text" name="customer[province]" placeholder="Province" id="order_form_province" required>

                                            <div class="form__error js-form-error--shipping-province hidden">* Enter a valid province</div>

                                        </div>
                                    
                                    </div>
                                    
                                    <div class="checkout_form__row">

                                        <div class="checkout__input--half">

                                            <input class="form__textfield form__textfield--full js-shipping-zipcode js-checkout-form-required" type="text" name="customer[zipcode]" placeholder="Postal code" id="order_form_zip" required>
                                            
                                            <div class="form__error js-form-error--shipping-zip hidden">* Enter a valid zip code</div>

                                        </div>

                                        <div class="checkout__input--half">

                                            <select class="form__textfield form__textfield--full form__select_menu js-shipping-country js-checkout-form-required" type="text" name="customer[country]" placeholder="Country" id="order_form_country" required>

                                                <?= getListCountry(); ?>

                                            </select>

                                            <div class="form__error js-form-error--shipping-country hidden">* Select a valid country</div>

                                        </div>
                                    
                                    </div>

                                </div>

                            </fieldset>            

                            <div class="checkout_form__footer">
                            
                                <button class="button--transaction checkout_form_footer__submit js-place-your-order" type="submit" value="Place your order">Place your order</button>

                            </div>

                        </div>

                    </div>

                    <div class="order_summary__container">
                    
                        <div class="order_summary__info">

                            <div class="checkout__summary_heading">

                                <h4 class="checkout__summary_title">
                                    
                                    <span class="checkout__summary__title">Order summary</span>

                                    <a class="order_summary__modify_order" href="cart.php" title="Modify order">Modify order</a>

                                </h4>

                            </div>

                            <div class="checkout__separator--page"></div>

                            <div class="orders_summary__wrapper">

                                <?php $iTotalPrice = 0.00; $iCounter = 0; ?>

                                <?php foreach ($_SESSION['sopranos']['order'] as $iKey => $aOrderItem): ?>

                                <?php $iSubtotalPrice = 0.00; ?>

                                <div class="order_summary_section js-order_summary_section" id="order_summary_section-<?= $iCounter; ?>">

                                    <div class="order_summary">

                                        <div class="order_summary_brand">

                                            <h5 class="order_summary_title"><?= $aSopranosBranches['name'].' - '.$aSopranosBranches['city']; ?></h5>

                                        </div>   
                                 
                                        <div class="order_summary_items">

                                            <div class="order_summary__item_image_container">

                                                <div class="order_summary__item_image_thumbnail">

                                                    <?php $aSqlType = queryHandler("SELECT pt.*, i.* FROM pizzas_type AS pt", "images AS i ON i.id = pt.image_id", "pt.quantity > 0 AND pt.id = '". $aOrderItem['type_id'] ."'", "pt.id ASC", 1); ?>

                                                    <img src="assets/images/layout/<?= $aSqlType['link']; ?>">  

                                                    <?php $iSubtotalPrice += $aSqlType['price'] * $aOrderItem['quantity']; ?>

                                                </div>

                                            </div>

                                            <div class="order_summary__item_name">

                                                <span class="order_summary__item_title"><?= $aSqlType['name']; ?></span>

                                                <ul class="order_summary__item_options">

                                                    <?php $aSqlSize = selectAllById('pizzas_size', $aOrderItem['size_id']); ?>
                                
                                                    <li class="order_summary__item_label"><?= $aSqlSize['size']; ?></li>

                                                    <?php $iSubtotalPrice += $aSqlSize['price'] * $aOrderItem['quantity']; ?>

                                                    <?php if(!empty($aOrderItem['topping_id'])): foreach ($aOrderItem['topping_id'] as $iToppingId => $sToppingName): ?>

                                                        <?php $iToppingPrice = selectAllById('pizzas_topping', $iToppingId); ?>
                                                        
                                                        <li class="order_summary__item_label"><?= $sToppingName; ?></li>
                                                        
                                                        <?php $iSubtotalPrice += $iToppingPrice['price'] * $aOrderItem['quantity']; ?>

                                                    <?php endforeach; endif; ?>
                                            
                                                </ul>

                                            </div>

                                            <div class="order_summary__item_quantity_and_price">

                                                <span class="order_summary__item_quantity" id="order_summary__item_quantity-<?= $iCounter; ?>"><?= $aOrderItem['quantity']; ?>x</span>
                                                            
                                                <span class="order_summary__item_price" id="order_summary__item_price-<?= $iCounter; ?>">€<?= number_format((float)$iSubtotalPrice / $aOrderItem['quantity'], 2, '.', ''); ?> EUR</span>

                                            </div>
                                        
                                        </div>

                                    </div>

                                    <div class="order_summary__breakdown">
        
                                        <div class="order_summary__discount js-order_summary__discount_wrapper hidden">

                                            <?php $iDiscountPrice = 0.00; ?>

                                            <div>

                                                <span class="order_summary__discount_label">Discount</span>

                                                <span class="order_summary__discount_tax js-discount_tax_label" id="order_summary__discount_tax-<?= $iCounter; ?>">(0%)</span>

                                            </div>
                        
                                            <span class="order_summary__discount_money text-right" id="order_summary__discount_money-<?= $iCounter; ?>">-€<?= $iDiscountPrice; ?> EUR</span>
                                                                                    
                                            <?php $iTotalPrice -= $iDiscountPrice; ?>

                                        </div>

                                        <div class="order_summary__delivery">
                                                        
                                            <?php $iDeliveryPrice = 0.00; ?>

                                            <span class="order_summary__delivery_label">Delivery</span>

                                            <span class="order_summary__delivery_value text-right" id="order_summary__delivery_value-<?= $iCounter; ?>">€<?= number_format((float)$iDeliveryPrice, 2, '.', ''); ?> EUR</span>

                                            <?php $iTotalPrice += $iDeliveryPrice; ?>

                                        </div>
                                                
                                        <div class="order_summary__subtotal">

                                            <span class="order_summary__subtotal_label">Sub-total</span>

                                            <div>

                                                <span class="order_summary__subtotal_price_without_discount text-right js-order_summary__subtotal_price_without_discount hidden" id="order_summary__subtotal_price_without_discount-<?= $iCounter; ?>">€<?= number_format((float)$iSubtotalPrice, 2, '.', ''); ?></span>

                                                <span class="order_summary__subtotal_price text-right" id="order_summary__subtotal_price-<?= $iCounter; ?>">€<?= number_format((float)$iSubtotalPrice, 2, '.', ''); ?> EUR</span>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="checkout__separator--page"></div>

                                </div>
                                                
                                <?php $iTotalPrice += $iSubtotalPrice; $iCounter++; ?>

                                <?php endforeach; ?>
        
                                <div class="order_summary__total_container">

                                    <div class="order_summary__total_label">
                                                    
                                        <span class="order_summary__total_label--text">Order total</span>

                                    </div>

                                    <div class="order_summary__total">
                                                                                                
                                        <span class="order_summary__total_price js-order_summary_total">€<?= number_format((float)$iTotalPrice, 2, '.', ''); ?> EUR</span>
                                    
                                    </div>

                                </div>

                            </div>

                        </div>
                    
                        <div class="order_summary__help">
                        
                            <div class="coupon_code_link__container">
                            
                                <button type="button" class="coupon_code_link js-coupon-button" id="coupon_code_link">I have a coupon code</button>
                                
                            </div>

                            <div class="coupon_code__wrapper js-coupon-code-wrapper hidden" id="coupon_code_box">
                                
                                <legend class="checkout__legend">Coupon Code</legend>
                                
                                <div class="coupon_code__container">
                                    
                                    <input class="form__textfield form__textfield--full js-coupon-code" placeholder="Enter your code here" type="text" name="coupon" id="order_form_coupon_code">
                                    
                                    <button type="button" class="button--apply js-coupon-apply" id="coupon_code_apply">Apply</button>

                                    <button type="button" class="button--apply js-coupon-applying disabled hidden" id="promo_code_applying">Applying…</button>

                                </div>
                                
                                <div class="coupon_code_message js-coupon-code-message"></div>

                            </div>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

<?php else: ?>

    <h1 class="text-center" style="margin-top: 5rem; margin-bottom: 50rem; font-size: 4rem;">Silence is golden.</h1>

<?php endif; ?>

<?php include_once("inc/footer.php") ?>

<?php print('<!--'.date("YmdHis").'-->'); $jsFiles = getFiles(); echo loadFiles($jsFiles); ?>

</body>
</html>