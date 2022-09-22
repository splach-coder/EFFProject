<?php
    try{
        
        session_start();
        require_once 'db.php';
        include '../assets/php/functions.php';
        
        
        function Validate($data){
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        
        $idCat = Validate($_POST['cat']);
        $idVille = Validate($_POST['city']);
        $title = Validate($_POST['title']);
        $prix = Validate($_POST['prix']);
        $desc = Validate($_POST['desc']);
        $login = Validate($_POST['login']);
        
        $userId;
    
        if(isset($_SESSION['user_id'])  &&  isset($_SESSION['user_id_infos'])  &&  isset($_SESSION['user_email'])){
            $userId = $_SESSION['user_id'];

            $sqlCheck = "SELECT count(*) as 'c' FROM `announcer` a, `infos_generales` i  
            WHERE a.id_infos_Generales = i.id_infos_Generales
            AND a.id_user = $userId
            AND i.id_Cat = $idCat";
            
            $number = getSingleValue($conn, $sqlCheck);
            
            if($number <= 2){
                
                ll($conn, $idCat, $idVille, $title, $desc, $prix, $userId);

                echo 'test';
                
            }else{
                echo 'limited announces 2 in each category';
            }
            
        }else {
            if($login == 'false') {
                $name  =   Validate($_POST['name']);
                $email  = Validate($_POST['email']);
                $pass = Validate($_POST['pass']);
                $tel = Validate($_POST['tel']);

                if(login($conn, $email, $pass, $name, $tel) != false){
                    
                    $userId = login($conn, $email, $pass, $name, $tel);

                    $sqlCheck = "SELECT count(*) as 'c' FROM `announcer` a, `infos_generales` i  
                    WHERE a.id_infos_Generales = i.id_infos_Generales
                    AND a.id_user = $userId
                    AND i.id_Cat = $idCat";
                
                    $number = getSingleValue($conn, $sqlCheck);
                
                    if($number <= 2){
                        ll($conn, $idCat, $idVille, $title, $desc, $prix, $userId);
                        
                        echo 'announcer';
                    }else{
                        echo 'limited announces 2 in each category';
                    }
                    
                } else {
                    echo 'incorrect informations'; 
                }
            }
        }
    
        
    }catch(Exception $e){
        echo $e->getMessage();
    }

    function ll($conn, $idCat, $idVille, $title, $desc, $prix, $userId){    
        $sql = "INSERT INTO `infos_generales` VALUES(NULL, $idCat, $idVille, 'new', '$title', '$desc', $prix)";
        $conn->exec($sql);

        $idinfo = getSingleValue($conn, "SELECT `id_infos_Generales` FROM `infos_generales` ORDER BY `id_infos_Generales` DESC LIMIT 1");

        $_SESSION['id_info'] =  $idinfo;

        $announce_time = getSingleValue($conn, "SELECT announce_time FROM params");
        
        $sql1 = "INSERT INTO `announcer` VALUES(NULL, $userId, $idinfo, CURRENT_TIME, date_add(CURRENT_DATE(), INTERVAL $announce_time month), 'moderation')";
        $conn->exec($sql1);
    }
    


    


    

    
