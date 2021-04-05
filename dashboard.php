<?php 
/* Copyright (c) - 2021 by Junyi Xie */	

    include_once("inc/connect.php");
    include_once("inc/functions.php");
    include_once("inc/class.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard | Sopranos</title>
    <meta charset="UTF-8">
    <meta name="author" content="Junyi Xie">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/images/favicon/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css?<?php echo date("YmdHis"); ?>" media="screen">
</head>
<body class="js-body">

<?php if (!isset($_SESSION['profile']['uid']) && !isset($_COOKIE['uid'])): sendLoginError(); endif; ?>

<?php $AccountKey = (!isset($_COOKIE['uid']) ? $_SESSION['profile']['uid'] : $_COOKIE['uid']); ?>

<div class="dashboard__container">

    <div class="dashboard__sidebar">

        <div class="dashboard__sidebar_inner">

            <div class="dashboard__sidebar_heading">

                <span class="dashboard__sidebar_logo">Sopranos</span>

            </div>

            <nav class="dashboard__sidebar_nav">

                <ul class="dashboard__sidebar_menu">

                    <li class="dashboard__sidebar_menu__item<?php if (!isset($_GET['go'])): ?> active<?php endif; ?>">

                        <a class="dashboard__sidebar_menu__item_link" href="dashboard.php"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a>

                    </li>

                    <li class="dashboard__sidebar_menu__item<?php if (isset($_GET['go']) && $_GET['go'] == 'branches'): ?> active<?php endif; ?>">
                    
                        <a class="dashboard__sidebar_menu__item_link" href="dashboard.php?go=branches"><i class="fas fa-map-marked-alt"></i><span>Branches</span></a>
                    
                    </li>

                    <li class="dashboard__sidebar_menu__item<?php if (isset($_GET['go']) && $_GET['go'] == 'coupons'): ?> active<?php endif; ?>">
                    
                        <a class="dashboard__sidebar_menu__item_link" href="dashboard.php?go=coupons"><i class="fas fa-ticket-alt"></i><span>Coupons</span></a>
                    
                    </li>

                    <li class="dashboard__sidebar_menu__item<?php if (isset($_GET['go']) && $_GET['go'] == 'stores'): ?> active<?php endif; ?>">
                    
                        <a class="dashboard__sidebar_menu__item_link" href="dashboard.php?go=stores"><i class="fas fa-store"></i><span>Stores</span></a>
                    
                    </li>

                    <li class="dashboard__sidebar_menu__item<?php if (isset($_GET['go']) && $_GET['go'] == 'customers'): ?> active<?php endif; ?>">
                    
                        <a class="dashboard__sidebar_menu__item_link" href="dashboard.php?go=customers"><i class="fas fa-users"></i><span>Customers</span></a>
                    
                    </li>

                    <li class="dashboard__sidebar_menu__item<?php if (isset($_GET['go']) && $_GET['go'] == 'payouts'): ?> active<?php endif; ?>">
                    
                        <a class="dashboard__sidebar_menu__item_link" href="dashboard.php?go=payouts"><i class="fas fa-money-check"></i><span>Payouts</span></a>
                    
                    </li>   
                    
                    <li class="dashboard__sidebar_menu__item<?php if (isset($_GET['go']) && $_GET['go'] == 'profile'): ?> active<?php endif; ?>">
                    
                        <a class="dashboard__sidebar_menu__item_link" href="dashboard.php?go=profile"><i class="fas fa-user-alt"></i><span>Profile</span></a>
                    
                    </li>  

                    <li class="dashboard__sidebar_menu__item">
                    
                        <a class="dashboard__sidebar_menu__item_link" href="dashboard.php?go=signout"><i class="fas fa-sign-out-alt"></i><span>Sign Out</span></a>
                    
                    </li>

                    <?php if (isset($_GET['go']) && $_GET['go'] == 'signout') accountLogout($AccountKey); ?>

                </ul>

                <ul class="dashboard__sidebar_submenu">

                    <li class="dashboard__sidebar_menu__item<?php if (isset($_GET['go']) && $_GET['go'] == 'settings'): ?> active<?php endif; ?>">
                        
                        <a class="dashboard__sidebar_menu__item_link" href="dashboard.php?go=settings"><i class="fas fa-cog"></i><span>Settings</span></a>
                    
                    </li>

                </ul>

            </nav>

        </div>

    </div>

    <div class="dashboard__content">

        <header class="dashboard__header">

            <div class="dashboard__actionbar">

                <div class="dashboard__actionbar_inner">

                    <div class="dashboard__actionbar_profile">

                        <?php $aAccounts = queryOperator("SELECT a.*, i.* FROM accounts AS a", "images AS i ON i.id = a.image_id", "a.id = '". $AccountKey ."'", "a.id ASC", 1); ?>
                        
                        <span class="dashboard__actionbar_name"><?= $aAccounts['fullname']; ?></span>

                        <?php if (!empty($aAccounts['link']) && !is_null($aAccounts)): ?>

                        <img class="dashboard__actionbar_avatar" src="<?= $aAccounts['link']; ?>">

                        <?php else: ?>

                        <div class="dashboard__actionbar_avatar"><span><?php echo getNameInitials($aAccounts['fullname']); ?></span></div>

                        <?php endif ?>

                    </div>

                </div>

            </div>

        </header>

        <section class="dashboard__overview">

            <div class="dashboard__overview_inner">

                <?php if (!isset($_GET['go'])): ?>

                    <!-- home -->
                    <h1>Hello!</h1>

                <?php elseif (isset($_GET['go']) && $_GET['go'] == 'branches'): ?>

                    <!-- branches -->
                    <h1>Branches!</h1>

                <?php elseif (isset($_GET['go']) && $_GET['go'] == 'coupons'): ?>

                    <!-- coupons -->
                    <h1>Coupons!</h1>

                <?php elseif (isset($_GET['go']) && $_GET['go'] == 'stores'): ?>

                    <!-- stores -->
                    <h1>Stores!</h1>

                <?php elseif (isset($_GET['go']) && $_GET['go'] == 'customers'): ?>

                    <!-- customers -->
                    <h1>Customers!</h1>

                <?php elseif (isset($_GET['go']) && $_GET['go'] == 'payouts'): ?>

                    <!-- payouts -->
                    <h1>Payouts!</h1>

                <?php elseif (isset($_GET['go']) && $_GET['go'] == 'profile'): ?>

                <h1 class="dashboard_page__heading"><?= $aAccounts['fullname']; ?>&apos;s Profile</h1>

                <div class="dashboard_page__account">

                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Saepe, non architecto qui fugit ex provident modi quia laboriosam magni accusantium. Nemo modi voluptatem tempora voluptatibus architecto culpa voluptate commodi cumque.</p>

                </div>

                <?php elseif (isset($_GET['go']) && $_GET['go'] == 'settings'): ?>

                <h1 class="dashboard_page__heading">Settings</h1>

                <div class="dashboard__notifications">
                    
                    <div class="dashboard__form_message dashboard__form_message--success">Successfully updated your profile.</div>
            
                </div>

                <div class="dashboard_page__account">

                    <section class="account__general_settings">

                        <div class="dashboard_section__header">
                            
                            <h1 class="dashboard_section__heading">Change Your Information</h1>
                        
                        </div>

                        <div class="dashboard_section__content">

                            <form class="account_info_form" action="" accept-charset="UTF-8" method="post">

                                <input type="hidden" name="token" value="<?php echo $AccountKey; ?>">

                                <input type="hidden" name="action" value="info">

                                <div class="account__user_info">

                                    <div class="account__user_name">

                                        <label class="form___label">Full Name</label>

                                        <input class="form__textfield--input" type="text" name="user[name]" value="<?php echo (!empty($aAccounts['fullname']) ? $aAccounts['fullname'] : ''); ?>" placeholder="Your Name">

                                    </div>

                                    <div class="account__user_phone">

                                        <label class="form___label">Phone Number</label>

                                        <input class="form__textfield--input" type="phone" name="user[phone]" value="<?php echo (!empty($aAccounts['phone']) ? $aAccounts['phone'] : ''); ?>" placeholder="Your Phone">

                                    </div>

                                </div>

                                <input class="button button-settings--update" type="submit" value="Update Info">

                            </form>

                        </div>
                        
                    </section>

                    <section class="account__email_settings">

                        <div class="dashboard_section__header">
                            
                            <h1 class="dashboard_section__heading">Change Your Email</h1>
                        
                        </div>

                        <div class="dashboard_section__content">

                            <form class="update_email_form" action="" accept-charset="UTF-8" method="post">

                            <input type="hidden" name="token" value="<?php echo $AccountKey; ?>">

                            <input type="hidden" name="action" value="email">

                            <div class="account__email_info">

                                <div class="account__user_new_email">

                                    <label class="form___label">New Email Address</label>

                                    <input class="form__textfield--input" type="email" name="email[new]" placeholder="Enter Email">

                                </div>

                                <div class="account__user_confirm_email">

                                    <label class="form___label">Confirm New Email Address</label>

                                    <input class="form__textfield--input" type="email" name="email[confirm]" placeholder="Confirm Email">

                                </div>

                                <div class="account__user_current_password">

                                    <label class="form___label">Current Password</label>

                                    <input class="form__textfield--input" type="password" name="email[password]" placeholder="Enter Password">

                                </div>

                            </div>

                            <input class="button button-settings--update" type="submit" value="Update Email">

                            </form>

                        </div>

                    </section>

                    <section class="account__password_settings">

                        <div class="dashboard_section__header">
                            
                            <h1 class="dashboard_section__heading">Change Your Password</h1>
                        
                        </div>

                        <div class="dashboard_section__content">

                            <form class="update_password_form" action="" accept-charset="UTF-8" method="post">

                                <input type="hidden" name="token" value="<?php echo $AccountKey; ?>">

                                <input type="hidden" name="action" value="password">

                                <div class="account__password_info">

                                    <div class="account__user_new_email">

                                        <label class="form___label">Current Password</label>

                                        <input class="form__textfield--input" type="password" name="password[current]" placeholder="Enter Password">

                                    </div>

                                    <div class="account__user_new_confirm_email">

                                        <label class="form___label">New Password</label>

                                        <input class="form__textfield--input" type="password" name="password[new]" placeholder="Must be longer than 6 characters">

                                    </div>

                                    <div class="account__user_current_password">

                                        <label class="form___label">Confirm New Password</label>

                                        <input class="form__textfield--input" type="password" name="password[confirm]" placeholder="Confirm Password">

                                    </div>

                                </div>

                                <input class="button button-settings--update" type="submit" value="Update Password">

                            </form>

                        </div>

                    </section>

                    <section class="account__avatar_settings">

                        <div class="dashboard_section__header">
                            
                            <h1 class="dashboard_section__heading">Change Your Avatar</h1>
                    
                        </div>

                        <div class="dashboard_section__content">

                            <form class="update_avatar_form" action="" accept-charset="UTF-8" method="post">

                                <input type="hidden" name="token" value="<?php echo $AccountKey; ?>">

                                <input type="hidden" name="action" value="avatar">

                                <div class="account__avatar_info">

                                <!-- TO DO, AVATAR UPLOAD IMAGE SHIT -->

                                </div>

                                <input class="button button-settings--update" type="submit" value="Update Avatar">

                            </form>

                        </div>

                    </section>

                </div>

                <?php endif; ?>

            </div>

        </section>

        <?php include_once('inc/footer.php'); ?>

    </div>

</div>

<?php print('<!--'.date("YmdHis").'-->'); $jsFiles = getFiles('assets/js', 'js'); echo loadFiles($jsFiles); ?>

</body>
</html>