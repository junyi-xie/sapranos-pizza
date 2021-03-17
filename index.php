<?php
    /* Copyright (c) - 2021 by Junyi Xie */	

    include_once("inc/connect.php");
    include_once("inc/functions.php");
    include_once("inc/class.php");

    printr($_SESSION);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Home · Sopranos Pizzabar</title>
    <meta charset="UTF-8">
    <meta name="author" content="Junyi Xie">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/images/favicon/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css?<?php echo date("YmdHis") ?>" media="screen">
</head>
<body>

<?php include_once("inc/header.php") ?>

<img id="big" src="assets/images/layout/pizza-vegetariano.png" style="height: 200px; width: 200px;">

<div id="thumbnails">
    <a href="assets/images/layout/pizza-sopranos-deluxe.png"><img src="assets/images/layout/pizza-sopranos-deluxe.png" style="height: 200px; width: 200px;"></a>
    <a href="assets/images/layout/pizza-tonno.png"><img src="assets/images/layout/pizza-tonno.png" style="height: 200px; width: 200px;"></a>
    <a href="assets/images/layout/pizza-quattro-formaggio.png"><img src="assets/images/layout/pizza-quattro-formaggio.png" style="height: 200px; width: 200px;"></a>
</div> 

<?php include_once("inc/footer.php") ?>

<?php print('<!--'.date("YmdHis").'-->'); $jsFiles = getFiles(); echo loadFiles($jsFiles); ?>

</body>
</html>