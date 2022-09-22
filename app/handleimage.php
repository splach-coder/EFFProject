<?php
    session_start();
    require_once 'db.php';

try{
    $id = $_GET['id'];

    $imagename = $_FILES['image']['name'];
    $imageTmpname = $_FILES['image']['tmp_name'];
    $targetpath = "../assets/images/".$imagename;
    if(move_uploaded_file($imageTmpname, $targetpath)){ 
        $sql = "INSERT INTO `images` VALUES ('$imagename', $id, NULL)";
        $conn->exec($sql);
        echo 'dazet';
    }
}catch(Exception $e){
    echo $e->getMessage();
}

    

    
    
