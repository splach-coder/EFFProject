<?php

        session_start();
        require_once '../../app/db.php';
        include '../../assets/php/functions.php';

        if(isset($_SESSION['user_id'])  &&  isset($_SESSION['user_id_infos'])  &&  isset($_SESSION['user_email']) && $_SESSION['role'] == 'admin'){
            

            function Validate($data){
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }
        
            //get username from form
            $id = $_POST['id'];
            $idinfo  = $_POST['id_info'];
            $fullname = Validate($_POST['name']);
            $Tel = Validate($_POST['tel']);
            $city = $_POST['city'];
            $email = Validate($_POST['email']);

            $sql = "UPDATE `users` SET `email`= ? WHERE id = ?";
            $stmt= $conn->prepare($sql);
            $stmt->execute([$email, $id]);

           
            $sqlinfo = "UPDATE `informations` SET `full_name`= ? ,`telephone`= ? ,`id_Ville`= ?  WHERE id = ?";
            $stmtinfo= $conn->prepare($sqlinfo);
            $stmtinfo->execute([$fullname, $Tel, $city, $idinfo]);
            
            if($stmtinfo != 0 && $stmt != 0){
                $_SESSION['name'] = $fullname;
                echo 'updated successfully';
            }else{
                echo 'some errors provides please retry after 5 mins';
            }
        }
