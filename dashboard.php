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

<?php if (!isset($_SESSION['profile']['uid']) && !isset($_COOKIE['uid'])): sendLoginError(); endif; ?>

<?php $AccountKey = (!isset($_COOKIE['uid']) ? $_SESSION['profile']['uid'] : $_COOKIE['uid']); $CurrentPage = "$_SERVER[REQUEST_SCHEME]://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>

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

                <h1 class="dashboard_page__heading">Dashboard</h1>

                <div class="dashboard_section__header">
                    
                    <h1 class="dashboard_section__heading">Welcome, <?= $aAccounts['fullname']; ?>!!!</h1>

                </div>

                    <div class="dashboard_section__content">

                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Saepe, non architecto qui fugit ex provident modi quia laboriosam magni accusantium. Nemo modi voluptatem tempora voluptatibus architecto culpa voluptate commodi cumque.</p><br/>

                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Saepe, non architecto qui fugit ex provident modi quia laboriosam magni accusantium. Nemo modi voluptatem tempora voluptatibus architecto culpa voluptate commodi cumque.</p><br/>

                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Saepe, non architecto qui fugit ex provident modi quia laboriosam magni accusantium. Nemo modi voluptatem tempora voluptatibus architecto culpa voluptate commodi cumque.</p><br/>

                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Saepe, non architecto qui fugit ex provident modi quia laboriosam magni accusantium. Nemo modi voluptatem tempora voluptatibus architecto culpa voluptate commodi cumque.</p><br/>

                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Saepe, non architecto qui fugit ex provident modi quia laboriosam magni accusantium. Nemo modi voluptatem tempora voluptatibus architecto culpa voluptate commodi cumque.</p><br/>

                </div>

                <?php elseif (isset($_GET['go']) && $_GET['go'] == 'branches'): ?>

                <h1 class="dashboard_page__heading">Branches</h1>

                <!-- DO THIS AS WELL TOMORROW, WED 4/7/2021. -->

                <?php elseif (isset($_GET['go']) && $_GET['go'] == 'coupons'): ?>

                <h1 class="dashboard_page__heading">Coupons Overview</h1>

                <div class="dashboard_page__coupons">

                    <section class="coupons__manage_settings">

                        <div class="dashboard_section__header">
                            
                            <h1 class="dashboard_section__heading">Manage</h1>

                            <!-- VIEW AND EDIT -->
                        
                        </div>

                        <div class="dashboard_section__content">

                            <?php 
                            
                                $Coupons = queryOperator('SELECT * FROM coupons', '', '', '');
                                printr($Coupons);

                            ?>
                        
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Officia harum libero aliquam atque excepturi non, labore qui cupiditate eaque ipsam architecto recusandae expedita impedit asperiores perferendis modi, sint in quas.</p>
                        
                        </div>

                    </section>

                    <section class="coupons__add_settings">

                        <div class="dashboard_section__header">
                            
                            <h1 class="dashboard_section__heading">Add</h1>
                        
                        </div>

                        <div class="dashboard_section__content">
                        
                            <!-- ADD NEW COUPON CODE ETC -->

                            <form class="" action="inc/ajax.php" accept-charset="UTF-8" method="post">
                        
                                <input type="hidden" name="action" value="coupon_code_create">

                                <input type="hidden" name="url" value="<?php echo $CurrentPage; ?>">
                        
                                <input class="button button-settings--update" type="submit" value="Make Coupon">

                            </form>
                        
                        </div>

                    </section>

                </div>

                <?php elseif (isset($_GET['go']) && $_GET['go'] == 'stores'): ?>

                <h1 class="dashboard_page__heading">Stores</h1>

                <?php elseif (isset($_GET['go']) && $_GET['go'] == 'customers'): ?>

                <h1 class="dashboard_page__heading">Customers</h1>

                <?php elseif (isset($_GET['go']) && $_GET['go'] == 'payouts'): ?>

                <h1 class="dashboard_page__heading">Payouts</h1>

                <?php elseif (isset($_GET['go']) && $_GET['go'] == 'profile'): ?>

                <h1 class="dashboard_page__heading">
                
                    <span class="dashboard_section__title"><?php echo $aAccounts['fullname']; ?>&apos;s Profile</span>

                    <a class="dashboard_section__edit" href="dashboard.php?go=settings">Edit Profile</a>
                
                </h1>

                <div class="dashboard_page__account">

                    <section class="profile__account_view">
            
                        <div class="dashboard_section__content">

                            <div class="profile__user_container">
                            
                                <div class="profile__user_avatar">
                            
                                    <?php echo (!empty($aAccounts['link']) && !is_null($aAccounts['link']) ? '<img class="profile__user_thumbnail" src="'.$aAccounts['link'].'"></img>' : '<div class="profile__user_thumbnail"><div class="profile__user_no_photo"><i class="fas fa-camera"></i><span>No Picture</span></div></div>'); ?>
                
                                </div>

                                <div class="profile__user_general">
                                
                                    <div class="profile__user_fullname">
                                    
                                        <label>Full Name</label>
                                        
                                        <span><?= (!empty($aAccounts['fullname']) ? $aAccounts['fullname'] : 'The Fool'); ?> | <?= (isset($aAccounts['admin']) && $aAccounts['admin'] === 1 ? '(Admin)' : '(Guest)'); ?></span>
                                    
                                    </div>

                                    <div class="profile__user_email">

                                        <label>Email Address</label>
                                    
                                        <span><?= (!empty($aAccounts['email']) ? $aAccounts['email'] : '-'); ?></span>

                                    </div>

                                    <div class="profile__user_phone">

                                        <label>Phone Number</label>

                                        <span><?= (!empty($aAccounts['phone']) ? $aAccounts['phone'] : '-'); ?></span>

                                    </div>

                                    <div class="profile__user_joined">

                                        <label>Date Joined</label>

                                        <span><?= (isset($aAccounts['account_created']) ? date("M j, Y", strtotime($aAccounts['account_created'])) : '-')?></span>

                                    </div>

                                </div>

                            </div>
                        
                        </div>

                    </section>

                </div>

                <?php elseif (isset($_GET['go']) && $_GET['go'] == 'settings'): ?>

                <h1 class="dashboard_page__heading">Settings</h1>

                <div class="dashboard__notifications"><?php flashMessage('feedback'); ?></div>

                <div class="dashboard_page__account">

                    <section class="account__general_settings">

                        <div class="dashboard_section__header">
                            
                            <h1 class="dashboard_section__heading">Change Your Information</h1>
                        
                        </div>

                        <div class="dashboard_section__content">

                            <form class="account_info_form" action="inc/ajax.php" accept-charset="UTF-8" method="post">

                                <input type="hidden" name="token" value="<?php echo $AccountKey; ?>">

                                <input type="hidden" name="action" value="update_general_info">

                                <input type="hidden" name="url" value="<?php echo $CurrentPage; ?>">

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

                            <form class="update_email_form" action="inc/ajax.php" accept-charset="UTF-8" method="post">

                            <input type="hidden" name="token" value="<?php echo $AccountKey; ?>">

                            <input type="hidden" name="action" value="update_email_address">

                            <input type="hidden" name="url" value="<?php echo $CurrentPage; ?>">

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

                            <form class="update_password_form" action="inc/ajax.php" accept-charset="UTF-8" method="post">

                                <input type="hidden" name="token" value="<?php echo $AccountKey; ?>">

                                <input type="hidden" name="action" value="update_password">

                                <input type="hidden" name="url" value="<?php echo $CurrentPage; ?>">

                                <div class="account__password_info">

                                    <div class="account__user_new_email">

                                        <label class="form___label">Current Password</label>

                                        <input class="form__textfield--input" type="password" name="password[current]" placeholder="Enter Password">

                                    </div>

                                    <div class="account__user_new_confirm_email">

                                        <label class="form___label">New Password</label>

                                        <input class="form__textfield--input" type="password" name="password[new]" placeholder="Must be longer than 6 characters">

                                    </div>

                                    <div class="account__user_confirm_password">

                                        <label class="form___label">Confirm New Password</label>

                                        <input class="form__textfield--input" type="password" name="password[confirm]" placeholder="Confirm Password">

                                    </div>

                                </div>

                                <input class="button button-settings--update" type="submit" value="Update Password">

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