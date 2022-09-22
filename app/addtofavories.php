<?php
    session_start();
    require 'db.php';
    $idannonce = $_POST['id'];
    $type = $_POST['type'];
    if(isset($_SESSION['user_id'])  &&  isset($_SESSION['user_id_infos'])  &&  isset($_SESSION['user_email'])){
        $user = $_SESSION['user_id'];
        if($type === 'add'){
            try{
                $query = "INSERT INTO `favories` VALUES(NULL, $idannonce, $user)";
                $conn->exec($query);
                echo true;
            }catch(PDOException $ex){
                echo $ex->getMessage();
            }
        }else{
            if($type === 'remove'){
            try{
                $query = "DELETE FROM `favories` WHERE id_annonce = $idannonce";
                $conn->exec($query);
                echo true;
            }catch(PDOException $ex){
                echo $ex->getMessage();
            } }
        }
    }else{
        echo false;
    }
