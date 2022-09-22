<?php
    require_once 'db.php';
    require '../assets/php/functions.php';

    
    $ip = $_POST['ip'];
    $city = $_POST['city'];
    
    $query = "SELECT EXISTS (SELECT ip FROM `visitors-counter` WHERE ip = '$ip')";
    if(getSingleValue($conn, $query) == 0){
        $sql = "INSERT INTO `visitors-counter`(`ip`, `city`) VALUES ('$ip', '$city')";
        $conn->exec($sql); 
    }


                    
