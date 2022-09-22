<?php

        session_start();
        require_once 'db.php';
        include '../assets/php/functions.php';
        
        if(isset($_SESSION['user_id'])  &&  isset($_SESSION['user_id_infos'])  &&  isset($_SESSION['user_email'])){
            $userinfo = $_SESSION['user_id_infos'];

            function Validate($data){
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }
        
            //get username from form
            $fullname = Validate($_POST['name']);
            $Tel = Validate($_POST['tel']);
            $city = $_POST['city'];
           
            $sql = "UPDATE `informations` SET `full_name`= ? ,`telephone`= ? ,`id_Ville`= ?  WHERE id = ?";
            $stmt= $conn->prepare($sql);
            $stmt->execute([$fullname, $Tel, $city, $userinfo]);
            
            if($stmt != 0){
                $_SESSION['name'] = $fullname;
                echo 'updated successfully';
            }else{
                echo 'some errors provides please retry after 5 mins';
            }
        }
