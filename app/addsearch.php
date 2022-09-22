<?php
    session_start();
    require 'db.php';
    try{
    $sql = "";

    $msg = $_POST['msg'];
    $user = -1;

    if(isset($_SESSION['user_id'])  &&  isset($_SESSION['user_id_infos'])  &&  isset($_SESSION['user_email'])){
        $user = $_SESSION['user_id'];
    }

    $sql = "INSERT INTO `searchs`(`search`, `id_user`) VALUES ('$msg',$user)";
    $stmt= $conn->prepare($sql);
    $stmt->execute();
        
    }catch(PDOException $e){
        echo $e->getMessage();
    }
