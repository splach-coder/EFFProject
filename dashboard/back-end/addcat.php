<?php
    session_start();
    require_once '../../app/db.php';

try{
    $name = $_GET["title"];
    $imagename = $_FILES['image']['name'];
    $imageTmpname = $_FILES['image']['tmp_name'];
    $targetpath = "../../assets/icons/".$imagename;
    if(move_uploaded_file($imageTmpname, $targetpath)){ 
        $sql = "INSERT INTO `categories`(`Name_Cat`, `icon`) VALUES ('$name','$imagename')";
        $conn->exec($sql);
        echo 'dazet';
    }
    echo 'madazet';
}catch(Exception $e){
    echo $e->getMessage();
}
