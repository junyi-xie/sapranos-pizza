<?php 
    /* Copyright (c) - 2021 by Junyi Xie */	

    include_once("inc/connect.php");
    include_once("inc/functions.php");
    include_once("inc/class.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sopranos</title>
    <meta charset="UTF-8">
    <meta name="author" content="Junyi Xie">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/images/favicon/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css?<?php echo date("YmdHis"); ?>" media="screen">
</head>
<body class="js-body">

<?php if(isset($_GET['go']) && $_GET['go'] == 'signup'): ?>

<div class="authentication">

    <div class="authentication__header">
    
        <div class="authentication_header__left">

            <a class="authentication_header__link" href="index.php">
            
                <img class="site__logo_sopranos hidden" src="assets/images/layout/sopranos-logo-header.png" alt="Sopranos Logo" title="Sopranos Pizzabar">
            
            </a>

        </div>

        <div class="authentication_header__right">

            <span class="login__text">Already have an account?</span>

            <a class="button-login-link" href="login.php">Log In</a>

        </div>

    </div>

    <div class="authentication__section authentication__info">

        <div class="authentication__info__image"></div>

    </div>

    <div class="authentication__section">

        <div class="authentication_section__content authentication_section__content_signup">

            <div class="authentication_section__body authentication_section__body_alt">

                <div class="authentication_section__body__header">

                    <h2 class="authentication_section__body__title">Signup to Sopranos</h2>

                </div>

                <div class="authentication__form_container">

                    <form class="js-signup-email-form" action="login.php?go=signup" accept-charset="UTF-8" method="post" id="signup_email_form">

                        <div class="email_auth_form">

                            <label class="form__label">Your name</label>

                            <input class="form__textfield__signup_input js-signup-required-field" type="text" name="name" placeholder="Full Name" required>

                            <div class="form__error js-signup-name-error hidden">* Enter your name</div>                        

                            <label class="form__label">Email</label>

                            <input class="form__textfield__signup_input js-signup-required-field" type="email" name="email" placeholder="you@example.com" required>

                            <div class="form__error js-signup-email-error hidden">* Enter a valid email address</div>

                            <label class="form__label">Password</label>

                            <input class="form__textfield__signup_input form__textfield__signup_password js-signup-required-field" type="password" name="password" placeholder="Password" required>

                            <div class="form__error js-signup-password-error hidden">* Enter a valid password</div>

                            <input class="form__textfield__signup_input form__textfield__confirm_password js-signup-required-field" type="password" name="password_confirmation" placeholder="Confirm Password" required>

                            <div class="form__error js-signup-password-confirm-error hidden">* Password confirmation doesn't match password</div>
                        
                            <div class="authentication__privacy_message">
                                
                                <p>By creating your account, you agree to our <a href="#" target="_blank">Terms of Service</a> and <a href="#" target="_blank">Privacy Policy</a>.</p>
                            
                            </div>

                            <input class="button button_signup_submit js-signup-email-submit" type="submit" value="Sign Up">

                        </div>
                    
                    </form>
                
                </div>

            </div>

        </div>

    </div>

</div>

<?php else: ?>

<div class="authentication">

    <div class="authentication__header">
        
        <div class="authentication_header__left">

            <a class="authentication_header__link" href="index.php">
            
                <img class="site__logo_sopranos" src="assets/images/layout/sopranos-logo-header.png" alt="Sopranos Logo" title="Sopranos Pizzabar">
            
            </a>

        </div>

        <div class="authentication_header__right authentication__signup">

            <a class="button-signup" href="login.php?go=signup">Create account</a>

        </div>

    </div>

    <div class="login__label">

        <h2 class="login__title">Login to Sopranos</h2>

    </div>            
    
    <div class="content_wrap" id="content_wrap">
    
        <div class="authentication__body">

            <div class="authentication__section authentication_login_form__section">

                <div class="authentication_section__content">

                    <div class="authentication_section__body">

                        <div class="authentication__form_container">

                            <form class="form_login__body js-email-login-form" action="login.php" accept-charset="UTF-8" method="post" id="form_login_wrapper">

                                <div class="email_auth_form">

                                    <div class="form__email-container">
                                
                                        <label class="login__form_label">Email</label>

                                        <input class="login__input" type="email" name="email" placeholder="Email" required>

                                        <div class="form__error js-login-email-error hidden">* Enter a valid email address</div>

                                    </div>

                                    <div class="form__password-container">

                                        <label class="login__form_label">Password</label>

                                        <input class="login__input" type="password" name="password" placeholder="Password" required>

                                        <div class="form__error js-login-password-error hidden">* Enter a valid password</div> 

                                    </div>

                                    <div class="form__option">

                                        <input class="cookie--wrapper" type="checkbox" name="cookie">
                                        
                                        <span>Always stay logged in?</span>
                                    
                                    </div>

                                    <div class="forn__footer_login">

                                        <input class="form__login--button js-email-login-submit" type="submit" value="Log In">
                                
                                    </div>

                                </div>
                            
                            </form>

                        </div>
                    
                    </div>
                
                </div>
            
            </div>

            <div class="authentication__section option_auth_login__form">

                <div class="authentication_section__content">

                    <div class="authentication_section__body">
                    
                        <div class="authentication__form_container_wrapper">
                        
                            <a class="button authentication__button--create" href="login.php?go=signup">
                            
                                <div class="auth_logo_icon"><i class="fas fa-user-plus"></i></div>

                                <div class="auth_login_message">Create an account</div>
                            
                            </a>
                        
                        </div>
                    
                    </div>
                
                </div>


            </div>

        </div>

        <div class="privacy__terms">

            <p>By logging in you agree to our <a href="#" target="_blank">Terms of Service</a> and <a href="#" target="_blank">Privacy Policy</a></p>
        
        </div>

    </div>

</div>

<?php endif; ?>

<?php print('<!--'.date("YmdHis").'-->'); $jsFiles = getFiles(); echo loadFiles($jsFiles); ?>

</body>
</html>