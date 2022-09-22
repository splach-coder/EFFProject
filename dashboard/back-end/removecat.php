<?php
    session_start();
    require_once '../../app/db.php';
    require_once '../..//assets/php/functions.php';

try{
    $id = $_POST["id"];
    $ope = $_POST["ope"];

    if($ope == "nbr"){
        $nbr = getSingleValue($conn, "SELECT COUNT(*) FROM `categories` c, `infos_generales` i  WHERE c.id_Cat = i.id_Cat AND c.id_Cat = $id");
        echo $nbr;
    }else{  
        if($ope == "delete"){
            try {

                $sqlinfo = "DELETE FROM `categories` WHERE id_Cat = $id";
                $stmtinfo= $conn->prepare($sqlinfo);
                $stmtinfo->execute();
                    
                if($stmtinfo != 0){           
                    echo 'deleted successfully';
                }else{
                    echo 'some errors provides please retry after 5 mins';
                }
                
            } catch (PDOException $ex) {
                echo $ex->getMessage();
            }
        }
        
    }
    
   
}catch(Exception $e){
    echo $e->getMessage();
}
