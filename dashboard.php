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

<div class="dashboard__container">

    <div class="dashboard__sidebar">

        <div class="dashboard__sidebar_inner">

            <div class="dashboard__sidebar_heading">

                <span class="dashboard__sidebar_logo">Sopranos</span>

            </div>

            <!-- TODO, FIX THE DROPDOWN MENU SHIT -->

            <ul class="dashboard__sidebar_menu">

                <li class="dashboard__sidebar_menu__item">

                    <a class="dashboard__sidebar_menu__item_link" href="dashboard.php"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a>

                </li>

                <li class="dashboard__sidebar_menu__item">
                
                    <a class="dashboard__sidebar_menu__item_link" href="dashboard.php?go=branches"><i class="fas fa-map-marked-alt"></i><span>Branches</span></a>
                
                </li>

                <li class="dashboard__sidebar_menu__item">
                
                    <a class="dashboard__sidebar_menu__item_link dashboard__sidebar_dropdown_toggle" href="dashboard.php?go=coupons"><i class="fas fa-ticket-alt"></i><span>Coupons</span></a>
                
                    <ul class="dashboard__sidebar_dropdown_submenu">

                        <li class="dashboard__sidebar_menu__item">
                
                            <a class="dashboard__sidebar_menu__item_link" href="dashboard.php?go=coupons&action=view"><i class="fas fa-store"></i><span>View</span></a>
                        
                        </li>

                        <li class="dashboard__sidebar_menu__item">
                
                            <a class="dashboard__sidebar_menu__item_link" href="dashboard.php?go=coupons&action=add"><i class="fas fa-store"></i><span>Add</span></a>
                        
                        </li>

                        <li class="dashboard__sidebar_menu__item">
                
                            <a class="dashboard__sidebar_menu__item_link" href="dashboard.php?go=coupons&action=view"><i class="fas fa-store"></i><span>View</span></a>
                        
                        </li>

                        <li class="dashboard__sidebar_menu__item">
                
                            <a class="dashboard__sidebar_menu__item_link" href="dashboard.php?go=coupons&action=add"><i class="fas fa-store"></i><span>Add</span></a>
                        
                        </li>

                    </ul>

                </li>

                <li class="dashboard__sidebar_menu__item">
                
                    <a class="dashboard__sidebar_menu__item_link" href="dashboard.php?go=stores"><i class="fas fa-store"></i><span>Stores</span></a>
                
                </li>

                <li class="dashboard__sidebar_menu__item">
                
                    <a class="dashboard__sidebar_menu__item_link" href="dashboard.php?go=customers"><i class="fas fa-users"></i><span>Customers</span></a>
                
                </li>

                <li class="dashboard__sidebar_menu__item">
                
                    <a class="dashboard__sidebar_menu__item_link" href="dashboard.php?go=payouts"><i class="fas fa-money-check"></i><span>Payouts</span></a>
                
                </li>   
                
                <li class="dashboard__sidebar_menu__item">
                
                    <a class="dashboard__sidebar_menu__item_link" href="dashboard.php?go=profile"><i class="fas fa-user-alt"></i><span>Profile</span></a>
                
                </li>  

                <li class="dashboard__sidebar_menu__item">
                
                    <a class="dashboard__sidebar_menu__item_link" href="dashboard.php?go=signout"><i class="fas fa-sign-out-alt"></i><span>Sign Out</span></a>
                
                </li>

            </ul>

        </div>

    </div>

    <div class="dashboard__content">

        <div class="dashboard__header">

            <div class="dashboard__actionbar">

                <div class="dashboard__actionbar_inner">

                    <div class="dashboard__actionbar_profile">

                        <?php $aAccount = queryOperator("SELECT a.*, i.* FROM accounts AS a", "images AS i ON i.id = a.image_id", "a.id = '". (!isset($_COOKIE['uid']) ? $_SESSION['profile']['uid'] : $_COOKIE['uid']) ."'", "a.id ASC", 1); ?>
                        
                        <span class="dashboard__actionbar_name"><?= $aAccount['fullname']; ?></span>

                        <img class="dashboard__actionbar_avatar" src="<?= $aAccount['link']; ?>">

                    </div>

                </div>

            </div>

        </div>

        <div class="dashboard__overview">

            <!-- if else for which page shit -->

        </div>


    </div>

</div>

<?php print('<!--'.date("YmdHis").'-->'); $jsFiles = getFiles('assets/js', 'js'); echo loadFiles($jsFiles); ?>

</body>
</html>