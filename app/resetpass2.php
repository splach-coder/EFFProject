<?php
    session_start();
    include 'db.php';
    include '../assets/php/functions.php';
   
    $id = $_GET["id"];
    
    if(isset($_POST['Lpass']) && isset($_POST['newLpass'])){
        $pass = $_POST['Lpass'];
        $newpass = $_POST['newLpass'];

        if(empty($pass) || empty($newpass)){
            header("Location: ../reseatPass2.php?error=passwords 're required&id=$id");
        }
        else{
            if($pass != $newpass){
                header("Location: ../reseatPass2.php?error=passwords doesn't matched&id=$id");
            }else{
               
                $stmt = $conn->prepare("UPDATE `users` SET `password` = $pass WHERE `id`=?");
                $stmt->execute([$id]);

                $sql1 = "INSERT INTO `msgfromadmin`(`to_user`, `msg`; `link`) VALUES ($id, 'Votre mot de passe est bien modifié', null)";
                $int = $conn->exec($sql1);
                
                header("Location: ../loginpage.php?success=login now");
            }
        }
    }
