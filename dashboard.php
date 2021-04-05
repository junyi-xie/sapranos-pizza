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

                    <?php if (isset($_GET['go']) && $_GET['go'] == 'signout') accountLogout((!isset($_COOKIE['uid']) ? $_SESSION['profile']['uid'] : $_COOKIE['uid'])); ?>

                </ul>
            </nav>

        </div>

    </div>

    <div class="dashboard__content">

        <header class="dashboard__header">

            <div class="dashboard__actionbar">

                <div class="dashboard__actionbar_inner">

                    <div class="dashboard__actionbar_profile">

                        <?php $aAccount = queryOperator("SELECT a.*, i.* FROM accounts AS a", "images AS i ON i.id = a.image_id", "a.id = '". (!isset($_COOKIE['uid']) ? $_SESSION['profile']['uid'] : $_COOKIE['uid']) ."'", "a.id ASC", 1); ?>
                        
                        <span class="dashboard__actionbar_name"><?= $aAccount['fullname']; ?></span>

                        <?php if (!empty($aAccount['link']) && !is_null($aAccount)): ?>

                        <img class="dashboard__actionbar_avatar" src="<?= $aAccount['link']; ?>">

                        <?php else: ?>

                        <div class="dashboard__actionbar_avatar"><span><?php echo getNameInitials($aAccount['fullname']); ?></span></div>

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

                <h1 class="dashboard_page__heading">Profile</h1>

                <div class="dashboard__notifications">
                    
                    <div class="dashboard__form_message dashboard__form_message--success">Successfully updated your profile.</div>
            
                </div>

                <div class="dashboard_page__account">

                    <section class="account__general_settings">

                        <div class="dashboard_section__header">
                            
                            <h1 class="dashboard_section__heading">Change Your Information</h1>
                        
                        </div>

                        <div class="dashboard_section__content">

                            <form action="">


                                <!-- put form blah blah -->

                            </form>

                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. A aspernatur aliquid architecto beatae voluptate ipsum incidunt cupiditate, saepe aperiam eaque obcaecati asperiores perspiciatis optio at reiciendis illo tempora exercitationem accusamus?</p>

                        </div>
                        
                    </section>

                    <section class="account__email_settings">

                        <div class="dashboard_section__header">
                            
                            <h1 class="dashboard_section__heading">Change Your Email</h1>
                        
                        </div>

                        <div class="dashboard_section__content">

                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Iste, ipsam earum dicta reprehenderit eos eaque quisquam mollitia natus autem nesciunt fugiat sed blanditiis dolor consequuntur, molestiae expedita, cum quo dignissimos.</p>

                        </div>

                    </section>

                    <section class="account__password_settings">

                        <div class="dashboard_section__header">
                            
                            <h1 class="dashboard_section__heading">Change Your Password</h1>
                        
                        </div>

                        <div class="dashboard_section__content">

                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Officiis, consequatur, atque, ipsum hic illum velit beatae ullam delectus eligendi qui at reiciendis facilis eaque adipisci. Omnis reiciendis tempora perspiciatis ratione.</p>

                        </div>

                    </section>

                    <section class="account__avatar_settings">

                        <div class="dashboard_section__header">
                            
                            <h1 class="dashboard_section__heading">Change Your Avatar</h1>
                    
                        </div>

                        <div class="dashboard_section__content">

                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Modi quos eaque expedita quam dolor eligendi optio magnam velit perspiciatis temporibus, rem nulla. Quisquam unde quo quam ad. Laboriosam, repellendus quas.</p>

                        </div>

                    </section>

                </div>

                <?php endif; ?>

            </div>

        </section>

    </div>

</div>

<?php print('<!--'.date("YmdHis").'-->'); $jsFiles = getFiles('assets/js', 'js'); echo loadFiles($jsFiles); ?>

</body>
</html>