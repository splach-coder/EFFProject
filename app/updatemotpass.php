<?php

        session_start();
        require_once 'db.php';
        include '../assets/php/functions.php';

        if(isset($_SESSION['user_id'])  &&  isset($_SESSION['user_id_infos'])  &&  isset($_SESSION['user_email'])){
            $userid = $_SESSION['user_id'];

            if(isset($_POST['id'])){
                $userid = $_POST['id'];
            }
        
            function Validate($data){
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }
        
            //get username from form
            $actuel = Validate($_POST['actuel']);
            $new = Validate($_POST['new']);
            $renew = Validate($_POST['renew']);


            if($new == $renew){
                if(getSingleValue($conn, "SELECT EXISTS (SELECT * FROM `users` WHERE password = '$actuel' and id = $userid )") == 1){
                           
                    $sql = "UPDATE `users` SET `password`= ?  WHERE id = ?";
                    $stmt= $conn->prepare($sql);
                    $stmt->execute([$new, $userid]);
        
                    if($stmt != 0){
                        echo 'votre mot de pass updated';
                    }else{
                        echo 'some errors provides please retry after 5 mins';
                    }
                }else{
                    echo 'votre actuel mot pass est incorrect';
                }
            }else{
                echo 'please set same passwords';
            }
        }
