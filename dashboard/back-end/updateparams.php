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
        $at = Validate($_POST['at']);
        $act  = Validate($_POST['act']);

        $sqlinfo = "UPDATE `params` SET `announce_time`=?,`accept_time`=?";
        $stmtinfo= $conn->prepare($sqlinfo);
        $stmtinfo->execute([$at, ($act * 60 * 1000)]);
        
        if($stmtinfo != 0){           
            echo 'updated successfully';
        }else{
            echo 'some errors provides please retry after 5 mins';
        }
    }
