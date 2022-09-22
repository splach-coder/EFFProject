<?php 
session_start();
require_once '../../app/db.php';

$id = $_POST['id'];
$user = $_POST['user'];

$sql1 = "UPDATE `announcer` SET status = 'active' where id_announcer = $id";
$int = $conn->exec($sql1);

if($int != 0){
    $msg = "INSERT INTO `msgfromadmin`(to_user, msg, link) VALUES($user, 'Votre annonce à été approuvée , maintenant les visiteurs peuvent la voir au marché', 'gallery.php?id=$id');";
    $conn->exec($msg);

    echo true;
}
