<?php
    session_start();
    require_once 'db.php';
    
    $id = $_POST['id'];
    $imagename = $_POST['image'];
    
    $targetpath = "../assets/images/".$imagename;
    


    if (file_exists($targetpath)) {
      if(unlink($targetpath)){
      $sql = "DELETE FROM `images` WHERE id_image = $id";
      $conn->exec($sql);}
    } else {
      echo 'Could not delete '.$targetpath.', file does not exist';
    }

    

    
    
