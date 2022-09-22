<?php
    session_start();
    require_once '../../app/db.php';
    require_once '../..//assets/php/functions.php';


    if(isset($_SESSION['user_id'])  &&  isset($_SESSION['user_id_infos'])  &&  isset($_SESSION['user_email']) && $_SESSION['role'] == 'admin'){

        $code = getSingleValue($conn, "SELECT password FROM `users` WHERE id = " . $_SESSION['user_id']);

        if(isset($_POST["code"])){
            if($code == $_POST["code"]){
                echo 'matched';
            }
        }

        
    }
