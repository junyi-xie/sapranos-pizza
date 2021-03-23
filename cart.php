<?php
    /* Copyright (c) - 2021 by Junyi Xie */	

    include_once("inc/connect.php");
    include_once("inc/functions.php");
    include_once("inc/class.php");   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Shopping Cart · Sopranos Pizzabar</title>
    <meta charset="UTF-8">
    <meta name="author" content="Junyi Xie">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/images/favicon/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css?<?php echo date("YmdHis"); ?>" media="screen">
</head>
<body class="js-body">

<?php include_once("inc/header.php") ?>

<?php if (empty($_SESSION['sopranos']['order'])): ?>
            
<div class="shopping_cart__container">

    <div class="shopping_cart--empty">
    
        <div class="empty_shopping_cart_container">
        
            <div class="empty_shopping_cart--center empty_shopping_cart--image"></div>

            <div class="empty_shopping_cart--center">

                <h1 class="empty_shopping_cart-title text-center">Your cart is empty.</h1>
            
            </div>

        </div>

    </div>

</div>

<?php else: ?>

<div class="site__content_container">

    <div class="site__wrapper">

        <div class="site__main">

            <div class="shopping_cart__container">

                <div class="shopping_cart__wrapper">

                    <div class="shopping_cart_items__container">
                    
                        <div class="shopping_cart__header">

                            <h2 class="shopping_cart__title">Shopping Cart</h2>

                        </div>
                    
                        <div class="shopping_cart_order__wrapper">
                        
                            <?php $iTotalPrice = 0.00; ?>

                            <?php foreach($_SESSION['sopranos']['order'] as $iKey => $aOrderItem):?>

                            <?php $iSubtotalPrice = 0.00; ?>

                            <div class="shopping_cart_item js-shopping_cart_item" shopping-cart-item-id="<?= $iKey; ?>" id="shopping_cart_item-<?= $iKey; ?>">

                                <div class="shopping_cart_item__view_container">

                                    <div class="shopping_cart_item__view">
                                    
                                        <div class="shopping_cart_item__image">

                                            <div class="shopping_cart_item__image--thumbnail">

                                                <?php $aSqlType = queryHandler("SELECT pt.*, i.* FROM pizzas_type AS pt", "images AS i ON i.id = pt.image_id", "pt.quantity > 0 AND pt.id = '". $aOrderItem['type_id'] ."'", "pt.id ASC", 1); ?>

                                                <img src="assets/images/layout/<?= $aSqlType['link']; ?>">

                                                <?php $iSubtotalPrice += $aSqlType['price'] * $aOrderItem['quantity']; ?>

                                            </div>

                                        </div>

                                        <div class="shopping_cart_item__detail">

                                            <div class="shopping_cart_item__product_title">

                                                <h2 class="shopping_cart_item__product_title--heading"><?= $aSqlType['name']; ?></h2>

                                            </div>
                                            
                                            <div class="shopping_cart_item__product_detail">

                                                <ul class="shopping_cart_item__product_detail--list">
                                                
                                                    <?php $aSqlSize = selectAllById('pizzas_size', $aOrderItem['size_id']); ?>
                                                    
                                                    <li class="shopping_cart_item__product_detail--label"><?= $aSqlSize['size']; ?></li>

                                                    <?php $iSubtotalPrice += $aSqlSize['price'] * $aOrderItem['quantity']; ?>

                                                    <?php if(!empty($aOrderItem['topping_id'])): foreach ($aOrderItem['topping_id'] as $iToppingId => $sToppingName): ?>

                                                        <?php $iToppingPrice = selectAllById('pizzas_topping', $iToppingId); ?>

                                                        <li class="shopping_cart_item__product_detail--label"><?= $sToppingName; ?></li>

                                                        <?php $iSubtotalPrice += $iToppingPrice['price'] * $aOrderItem['quantity']; ?>

                                                    <?php endforeach; endif; ?>

                                                </ul>
                                                
                                            </div>
                                        
                                        </div>

                                        <div class="shopping_cart_item__quantity">
                                        
                                            <span class="shopping_cart_item__quantity_label"><?= $aOrderItem['quantity']; ?>x</span>

                                        </div>

                                        <div class="shopping_cart_item__price">

                                            <span class="shopping_cart_item__price_label">€<?= number_format((float)$iSubtotalPrice / $aOrderItem['quantity'], 2, '.', ''); ?></span>
                                        
                                        </div>

                                        <div class="shopping_cart_item__actions">
                                        
                                            <button type="button" class="shopping_cart_item__actions_button button--small button_cart--edit js-edit_cart_item" shopping-cart-item-id="<?= $iKey; ?>" id="shopping_action_edit-<?= $iKey; ?>">Edit</button>

                                            <button type="button" class="shopping_cart_item__actions_button button--small button_cart--cancel js-cancel_cart_item hidden" shopping-cart-item-id="<?= $iKey; ?>" id="shopping_action_cancel-<?= $iKey; ?>">Cancel</button>

                                            <button type="button" class="shopping_cart_item__actions_button button--small button_cart--update js-update_cart_item hidden" shopping-cart-item-id="<?= $iKey; ?>" id="shopping_action_update-<?= $iKey; ?>">Save</button>

                                        </div>

                                    </div>

                                    <div class="shopping_cart_item__edit js-shopping_cart_item__edit hidden">

                                        <div class="shopping_cart_item__triangle"></div>

                                        <div class="shopping_cart_item__remove_container">
                                    
                                            <div class="shopping_cart_item__remove">
                                            
                                                <button type="button" class="shopping_cart_item__remove-button--remove js-remove_cart_item" shopping-cart-item-id="<?= $iKey; ?>">Remove</button>
                                                            
                                            </div>
                                            
                                            <div class="shopping_cart_item__edit--right">
                                            
                                                <div class="shopping_cart_item__size">
                                                
                                                    <div class="shopping_cart_item__size--label">Size:</div>

                                                    <select class="shopping_cart_item__size--select js-shopping_cart_item__size--select" id="shopping_cart_item--select-<?= $iKey; ?>">

                                                        <?php foreach($aSizePizzas as $key => $aSize): ?>

                                                        <?php if($aSize['size'] == $aSqlSize['size']): ?>

                                                            <option value="<?= $aSize['id']; ?>" selected="selected"><?= $aSize['size']; ?></option>

                                                        <?php continue; endif; ?>
                                                                                                                    
                                                        <option value="<?= $aSize['id']; ?>"><?= $aSize['size']; ?></option>

                                                        <?php endforeach; ?>

                                                    </select>

                                                </div>
                                                
                                                <div class="shopping_cart_item__quantity">
                                                        
                                                    <div class="shopping_cart_item__quantity--label">Quantity:</div>

                                                    <input class="shopping_cart_item__quantity--input js-shopping_cart_item__quantity--input" type="number" min="1" max="999" value="<?= $aOrderItem['quantity']; ?>" id="shopping_cart_item__quantity-<?= $iKey; ?>">

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>
                            
                            </div>
                            
                            <?php $iTotalPrice += $iSubtotalPrice; ?>

                            <?php endforeach; ?>
                        
                        </div>

                    </div>

                    <div class="shopping_cart_summary__container">

                        <div class="shopping_cart_summary">
                        
                            <div class="shopping_cart_summary__detail shopping_cart_summary__detail--subtotal">
                            
                                <div class="detail__label">

                                    <span class="checkout_item_label">Cart Subtotal</span>

                                    <span class="checkout_item_count">- <?= $iShoppingCartCount; ?> item(s)</span>

                                </div>

                                <div class="detail__value">

                                    <span class="checkout_item_price">€<?= number_format((float)$iTotalPrice, 2, '.', ''); ?></span>

                                </div>

                            </div>

                            <div class="shopping_cart_summary__message">
                                Subtotal includes VAT. Shipping will be calculated on checkout.
                            </div>
                        
                            <div class="shopping_cart_summary__button">
                            
                                <a class="shopping_cart_summary__submit button--transaction" href="checkout.php">Proceed to Checkout</a>
                            
                            </div>

                        </div>
                
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?php endif; ?>

<?php include_once("inc/footer.php") ?>

<?php print('<!--'.date("YmdHis").'-->'); $jsFiles = getFiles(); echo loadFiles($jsFiles); ?>

</body>
</html>