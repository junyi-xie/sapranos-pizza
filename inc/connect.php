<?php
    /* Copyright (c) - 2021 by Junyi Xie */	

    if(session_status() == PHP_SESSION_NONE )
    {
        session_start();
    }

    date_default_timezone_set('Europe/Amsterdam');

    $hostname 	= "127.0.0.1";
    $username	= "root";
    $password 	= "";
    $dbname		= "sopranos";

    try {
        $pdo = new PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8mb4;", $username, $password, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
        
    } catch(PDOException $e) {

        die("Connection failed: ". $e->getMessage());        
    }

?>