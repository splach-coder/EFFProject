<?php
    try{
        
        session_start();
        require_once 'db.php';
        
        $info = $_POST['id_info'];
        $title = $_POST['title']; 
        $prix = $_POST['prix'];   
        $desc = $_POST['desc'];  
        
        
        if(isset($_SESSION['user_id'])  &&  isset($_SESSION['user_id_infos'])  &&  isset($_SESSION['user_email'])){
            
            $sql1 = "UPDATE `infos_generales` SET `titre_annonce`='$title',`description`='$desc',`prix`='$prix' WHERE `id_infos_Generales` = $info";
            $conn->exec($sql1);

            echo true;
        }else{
            echo false;
        }    
        
    }catch(Exception $e){
        echo $e->getMessage();
    }
    


    


    

    
