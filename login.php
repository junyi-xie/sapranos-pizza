<?php 
    /* Copyright (c) - 2021 by Junyi Xie */	

    include_once("inc/connect.php");
    include_once("inc/functions.php");
    include_once("inc/class.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login · Sopranos Pizzabar</title>
    <meta charset="UTF-8">
    <meta name="author" content="Junyi Xie">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/images/favicon/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css?<?php echo date("YmdHis"); ?>" media="screen">
</head>
<body class="js-body">

<?php if(isset($_GET['go']) && $_GET['go'] == 'signup'): ?>

    <h1>Signup!</h1>

    <!-- create sign up page -->

<?php else: ?>

    <div class="authentication">

        <div class="authentication__header">
            
            <div class="authentication_header__left">

                <a href="index.php">
                
                    <img class="site__logo_sopranos" src="assets/images/layout/sopranos-logo-header.png" alt="Sopranos Logo" title="Sopranos Pizzabar">
                
                </a>

            </div>

            <div class="authentication_header__right">

                <a class="button-signup" href="login.php?go=signup">Create account. (W.I.P)</a>

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

                <div class="authentication__section">

                    Lorem ipsum, dolor sit amet consectetur adipisicing elit. Aut rem alias tempora exercitationem deleniti quam facere, praesentium ex amet molestiae aspernatur non quibusdam obcaecati quas, est corrupti sint autem eveniet.
                
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