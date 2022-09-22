<?php
    session_start();
    require 'db.php';
    
    if(isset($_POST['submit'])){
        $imageCount = count($_FILES['image']['name']);
        for ($i=0; $i < $imageCount; $i++) { 
            $imagename = $_FILES['image']['name'][$i];
            $imageTmpname = $_FILES['image']['tmp_name'][$i];
            $targetpath = "../assets/images/".$imagename;
            if(move_uploaded_file($imageTmpname, $targetpath)){
                $id = $_SESSION['id_info'];
                $sql = "INSERT INTO `images` VALUES ('$imagename', $id, NULL)";
                $conn->exec($sql);
            }
        }

        header("Location: ../firstpage.php");
    }

    
    
        
 

    
    
