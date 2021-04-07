    /* Copyright (c) - 2021 by Junyi Xie */	

    $(document).ready(function(){

        $('.js-branches-switch-favorite').click(function(event) {
            event.preventDefault();

            var branches_id = $(this).parent().parent().attr('branches-id');

            if (confirm('Are you sure you want to make this the main branch?')) {
                $.ajax({
                    url:"inc/ajax.php",
                    type: "post",
                    data: {
                        action: 'change_favorite_branches',
                        key: branches_id,
                    },
                    success: function(result){
                        switch (result) {
                            case 'true':
                                location.reload(); 
                            break;
                        }
                    },
                });
            }
        });


        $('.js-coupon-remove-item').click(function(event) {
            event.preventDefault();

            var key = $(this).parent().parent().attr('coupon-id');

            if (confirm('Are you sure you want to delete this coupon code?')) {
                $.ajax({
                    url:"inc/ajax.php",
                    type: "post",
                    data: {
                        action: 'remove_coupon_delete',
                        coupon_id: key,
                    },
                    success: function(result){
                        switch (result) {
                            case 'true':
                                location.reload(); 
                            break;
                        }
                    },
                });
            }
        });


        $('.js-coupon-field-required').on('input', function(event) {
            event.preventDefault();

            var button = $('.js-coupon-create-button');
            var required = true;

            $('.js-coupon-field-required').each(function() {
                if (!$(this).val()) {

                    required = false;
                } 
            });

            if (required) {
                button.addClass('disabled').removeClass('disabled');
            } else if (!required) {
                button.removeClass('disabled').addClass('disabled');
            }
        });


        $(".js-signup-email-submit").click(function(event) {
            event.preventDefault();

            var signup_form = $('.js-signup-email-form');
    
            $.ajax({
                type: 'post',
                url: 'inc/ajax.php',
                data: {
                    action: 'create_new_account_signup',
                    signup: signup_form.serialize(),
                },
                success: function(result) {
                    data = JSON.parse(result);

                    var message = $('.js-email-login-error-container');             

                    var name = $('.js-signup-name-error');
                    var email = $('.js-signup-email-error');
                    var password = $('.js-signup-password-error');
                    var password_confirm = $('.js-signup-password-confirm-error');

                    var name_input = name.prev();
                    var email_input = email.prev();
                    var password_input = password.prev();
                    var password_confirm_input = password_confirm.prev();


                    name.removeClass('hidden').addClass('hidden');
                    name_input.addClass('error--inline').removeClass('error--inline');

                    email.removeClass('hidden').addClass('hidden');
                    email_input.addClass('error--inline').removeClass('error--inline');

                    password.removeClass('hidden').addClass('hidden');
                    password_input.addClass('error--inline').removeClass('error--inline');
                    
                    password_confirm.removeClass('hidden').addClass('hidden');
                    password_confirm_input.addClass('error--inline').removeClass('error--inline');

                    message.removeClass('hidden').addClass('hidden');

                    if (data === true) {

                        signup_form[0].reset();
                        message.removeClass('form__message--success').addClass('form__message--success');
                        message.html('Account successfully created, click <a href="login.php">here</a> to login.');
                        message.addClass('hidden').removeClass('hidden');

                    } else if(data !== false) {
                        $.each(data, function(key, value) {
                            switch (key) {
                                case 'name':
                                    name.html(value).addClass('hidden').removeClass('hidden');
                                    name_input.removeClass('error--inline').addClass('error--inline');
                                break;
                                case 'email':
                                    email.html(value).addClass('hidden').removeClass('hidden');
                                    email_input.removeClass('error--inline').addClass('error--inline');
                                break;
                                case 'password':
                                    password.html(value).addClass('hidden').removeClass('hidden');
                                    password_input.removeClass('error--inline').addClass('error--inline');
                                break;
                                case 'password_confirmation':
                                    password_confirm.html(value).addClass('hidden').removeClass('hidden');
                                    password_confirm_input.removeClass('error--inline').addClass('error--inline');
                                break;
                            } 
                        });
                    } else {

                        signup_form[0].reset();
                        message.removeClass('form__message--error').addClass('form__message--error');
                        message.html('Something went wrong, please try again...');
                        message.addClass('hidden').removeClass('hidden');
                    }
                }
            });
        });


        $(".js-email-login-submit").click(function(event) {
            event.preventDefault();

            var login_form = $('.js-email-login-form');

            $.ajax({
                type: 'post',
                url: 'inc/ajax.php',
                data: {
                    action: 'login_verify_account',
                    info: login_form.serialize(),
                },
                success: function(result) {
                    data = JSON.parse(result);

                    var message = $('.js-email-login-error-container');

                    var email_error = $('.js-login-email-error');
                    var email_input = email_error.prev();
                    var password_error = $('.js-login-password-error');
                    var password_input = password_error.prev();

                    
                    email_error.removeClass('hidden').addClass('hidden');
                    email_input.addClass('error--inline').removeClass('error--inline');
                    password_error.removeClass('hidden').addClass('hidden');
                    password_input.addClass('error--inline').removeClass('error--inline');

                    message.removeClass('hidden').addClass('hidden');

                    if (data === true) {

                        login_form[0].reset();
                        window.location.href = "dashboard.php?from=login&method?=email&auth=false"; 
                                               
                    } else if (data !== false) {
                        $.each(data, function(key, value) {
                            switch (key) {
                                case 'email':
                                    email_input.removeClass('error--inline').addClass('error--inline');
                                    email_error.html(value).addClass('hidden').removeClass('hidden');
                                break;
                                case 'password':
                                    password_input.removeClass('error--inline').addClass('error--inline');
                                    password_error.html(value).addClass('hidden').removeClass('hidden');
                                break;
                            } 
                        });
                    } else {

                        login_form[0].reset();
                        message.removeClass('form__message--error').addClass('form__message--error');
                        message.html('Something went wrong, please try again...');
                        message.addClass('hidden').removeClass('hidden');
                    }
                }
            });
        });
        

        $(".js-place-your-order").click(function(event) {
            event.preventDefault();

            var form = $('.js-order_form_container');
            var validate = true;

            $('.js-checkout-form-required').each(function() {
                if (!$(this).val()) {

                    validate = false;
                    $(this).removeClass('error--inline').addClass('error--inline');
                    $(this).next().addClass('hidden').removeClass('hidden');
                } 
            });

            if (validate) {
                $.ajax({
                    type: 'post',
                    url: 'inc/ajax.php',
                    data: {
                        action: 'save_customer_information',
                        data: form.serialize(),
                    },
                    success: function(result) {
                        switch (result) {
                            case 'true':
                                form.submit();
                            break;
                            default:
                                alert('Something went wrong...?! Please close your browser and start your order process over.');
                            break;                    
                        }
                    }
                });
            }
        })


        $(".js-add-to-cart").click(function(event) {
            event.preventDefault();

            var form = $('.js-shop_form_container');
            var pizza = $('.js-product__type_dropdown');

            if (pizza[0].checkValidity()) {
                $.ajax({
                    type: 'post',
                    url: 'inc/ajax.php',
                    data: {
                        action: 'save_customer_order',
                        cart: form.serialize(),
                    },
                    success: function(result) {
                        switch (result) {
                            case 'true':
                                form.submit();
                            break;
                            default:
                                alert('Something went wrong...?! Please close your browser and start your order process over.');
                            break;                    
                        }
                    }
                });
            } else {
                $('.js-form-error--shop-select-product').addClass('hidden').removeClass('hidden');
            }
        });


        $(".js-modal_close_button").click(function(event) {
            event.preventDefault();

            $('.js-modal_container').addClass('modal--inactive');
            $('.js-modal_overlay').addClass('hidden');
            $('.js-body').removeClass('modal--open');
        });


        $(".js-stack__nav_item .js-stack__nav_thumbnail").click(function(event) {
            event.preventDefault();

            var image_id = $(this).attr('data-shop-product-id');
            
            $('.js-stack__nav_item').removeClass('image_stack__nav_item--active');
            $(this).parent().addClass('image_stack__nav_item--active');
            $('.js-image_stack__image').attr('src', $(this).attr('src'));

            $('.js-product-thumbnails').removeClass('product__single--active');
            $('.js-product__type_dropdown .js-product_option[value="' + image_id + '"]').prop('selected', true);
            $('#product_thumbnail-' + image_id).addClass('product__single--active');
        });


        $(".js-product__type_dropdown").change(function(event) {
            event.preventDefault();

            var select_id = $(this).val();

            $('.js-product-thumbnails').removeClass('product__single--active');
            $('#product_thumbnail-' + select_id).addClass('product__single--active');

            $('.js-stack__nav_item').removeClass('image_stack__nav_item--active');
            $('.js-image_stack__image').attr('src', $('#product_nav_thumbnail-' + select_id).attr('src'));
            $('#product_nav_thumbnail-' + select_id).parent().addClass('image_stack__nav_item--active');
        });


        $(".js-product-thumbnails").click(function(event) {
            event.preventDefault();

            var product_id = $(this).attr('data-shop-product-id');

            $('.js-product-thumbnails').removeClass('product__single--active');
            $(this).addClass('product__single--active');
            $('.js-product__type_dropdown .js-product_option[value="' + product_id + '"]').prop('selected', true);

            $('.js-stack__nav_item').removeClass('image_stack__nav_item--active');
            $('.js-image_stack__image').attr('src', $('#product_nav_thumbnail-' + product_id).attr('src'));
            $('#product_nav_thumbnail-' + product_id).parent().addClass('image_stack__nav_item--active');
        });


        $(".js-edit_cart_item").click(function(event) {
            event.preventDefault();

            $(this).removeClass('hidden').addClass('hidden');
            $(this).next().addClass('hidden').removeClass('hidden');
            $(this).parent().parent().next().addClass('hidden').removeClass('hidden');
        });


        $(".js-cancel_cart_item").click(function(event) {
            event.preventDefault();

            $(this).removeClass('hidden').addClass('hidden');
            $(this).prev().addClass('hidden').removeClass('hidden');
            $(this).parent().parent().next().removeClass('hidden').addClass('hidden');
        });


        $(".js-remove_cart_item").click(function(event) {
            event.preventDefault();

            $.ajax({
                url:"inc/ajax.php",
                type: "post",
                data: {
                    action: 'remove_order_item',
                    key: $(this).attr('shopping-cart-item-id'),
                },
                success: function(result){
                    switch (result) {
                        case 'true':
                            location.reload(); 
                        break;
                    }
                },
            });
        });


        $(".js-update_cart_item").click(function(event) {
            event.preventDefault();

            var edit = $(this).parent().parent().next().children().last().children().last().children();
            var select = edit.first().children().last();
            var input = edit.last().children().last();

            $.ajax({
                url:"inc/ajax.php",
                type: "post",
                data: {
                    action: 'update_order_item',
                    quantity: input.val(),
                    size: select.val(),
                    key: $(this).attr('shopping-cart-item-id'),
                },
                success: function(result){
                    switch (result) {
                        case 'true':
                            location.reload(); 
                        break;
                    }
                },
            });
        });


        $('.js-shopping_cart_item__size--select').change(function(event){
            event.preventDefault();

            var button = $(this).parent().parent().parent().parent().parent().children().first().children().last();
            var option = $(this).find("option:selected").prop('defaultSelected');

            var field = $(this).parent().next().children().last();
            var input = field.prop('defaultValue');
            var value = field.val();

            if (input === value) {
                switch (option) {
                    case true:
                        button.find('.js-cancel_cart_item').addClass('hidden').removeClass('hidden');
                        button.find('.js-update_cart_item').removeClass('hidden').addClass('hidden');
                    break;
                    case false:
                        button.find('.js-cancel_cart_item').removeClass('hidden').addClass('hidden');
                        button.find('.js-update_cart_item').addClass('hidden').removeClass('hidden');
                    break;
                }
            }
        });


        $('.js-shopping_cart_item__quantity--input').on('input', function(event){
            event.preventDefault();

            var button = $(this).parent().parent().parent().parent().parent().children().first().children().last();
            var value = $(this).prop('defaultValue');
            var input = $(this).val();

            var select = $(this).parent().prev().children().last();
            var option = select.find("option:selected").prop('defaultSelected');

            if(option) {
                switch (input) {
                    case value:
                        button.find('.js-cancel_cart_item').addClass('hidden').removeClass('hidden');
                        button.find('.js-update_cart_item').removeClass('hidden').addClass('hidden');
                    break;
                    default:
                        button.find('.js-cancel_cart_item').removeClass('hidden').addClass('hidden');
                        button.find('.js-update_cart_item').addClass('hidden').removeClass('hidden');
                    break;
                }
            }
        });


        $("#coupon_code_link").click(function(event) {
            event.preventDefault();

            $(this).addClass("hidden");
            $('.js-coupon-code-wrapper').removeClass("hidden");
        });


        $("#coupon_code_apply").click(function(event) {
            event.preventDefault();

            var coupon_value = $('.js-coupon-code');
            var coupon_apply = $('.js-coupon-apply');
            var coupon_apply_secondary = $('.js-coupon-applying');

            coupon_apply.addClass('hidden');
            coupon_apply_secondary.removeClass('hidden');
            coupon_value.prop('disabled', true);

            setTimeout(function() {
                $.ajax({
                    url:"inc/ajax.php",
                    type: "post",
                    data: {
                        action: 'apply_coupon',
                        code: coupon_value.val(),
                    },
                    success: function(result){
                        switch (result) {
                            case 'null':
                                $('.js-coupon-code-message').removeClass('success').addClass('failure').html('Not a valid coupon code.');    
                            break;
                            default:
                                $.ajax({
                                    type: "post",
                                    url: "inc/ajax.php",
                                    data: {
                                        action: 'get_coupon',
                                        coupon_id: result,
                                    },
                                    success: function (data) {

                                        var coupon_data = JSON.parse(data);
                                        var new_total_price = 0.00;

                                        switch (coupon_data.type) {
                                            case 1:           
                                            
                                                $('.js-order_summary__discount_wrapper').removeClass('hidden');
                                                $('.js-discount_tax_label').html('('+coupon_data.discount+'%)');
 
                                                $('.js-order_summary_section').each(function(index) {
                                                    if (!$('#order_summary_section-'+index).length) { index++; } 

                                                    var item_quantity = parseInt($('#order_summary__item_quantity-'+index).text().replace(/[^0-9\.]/g, ''));
                                                    var item_price = parseFloat($('#order_summary__item_price-'+index).text().replace(/[^0-9\.]/g, ''));
                                                    
                                                    var new_price = ((item_price * ((100 - coupon_data.discount) / 100)) * item_quantity);
                                                    var discount_price = ((item_price * item_quantity) - new_price);

                                                    new_total_price += new_price;

                                                    $('#order_summary__discount_money-'+index).html('-€'+discount_price.toFixed(2)+' EUR');
                                                    $('#order_summary__subtotal_price-'+index).html('€'+new_price.toFixed(2)+' EUR');
                                                });

                                                $('.js-order_summary__subtotal_price_without_discount').removeClass('hidden');
                                                $('.js-order_summary_total').html('€'+new_total_price.toFixed(2)+' EUR');
                                                $('.js-coupon-code-message').removeClass('failure').addClass('success').html('Coupon code applied.');

                                            break;
                                        }
                                    }
                                });
                            break;
                        }
                    },
                });

                coupon_apply_secondary.addClass('hidden');
                coupon_apply.removeClass('hidden');
                coupon_value.prop('disabled', false);

            }, 1500);
        });
        
        
        $(".form__textfield").blur(function(event) {
            event.preventDefault();

            var input = $(this);
            var email = $('.js-checkout-email');
            var email_error = $('.js-form-error--checkout-email');

            switch (input.attr('id')) {
                case 'order_form_email':
                    $.ajax({
                        url:"inc/ajax.php",
                        type: "post",
                        data: {
                            action: 'validate_email',
                            email: input.val(),
                        },
                        success: function(result){
                            switch (result) {
                                case 'true':
                                    email.removeClass('error--inline');
                                    email_error.addClass('hidden');
                                break;
                                case 'false':
                                    email.addClass('error--inline');
                                    email_error.removeClass('hidden');
                                break;
                            }
                        },
                    });
                break;
                case 'order_form_phone':    
                case 'order_form_first_name':
                case 'order_form_last_name': 
                case 'order_form_address': 
                case 'order_form_city':
                case 'order_form_province':
                case 'order_form_zip':
                case 'order_form_country':
                    if (!input.val()) {
                        input.addClass('error--inline');
                        input.next().removeClass('hidden');
                    }  else {
                        input.removeClass('error--inline');
                        input.next().addClass('hidden');
                    }
                break;
            }
        });


        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    });