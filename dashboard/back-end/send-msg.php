<?php 
session_start();
require_once '../../app/db.php';

$id = $_POST['id'];
$msg = $_POST['msg'];

$sql1 = "INSERT INTO `msgfromadmin`(`to_user`, `msg`) VALUES ($id, '$msg')";
$int = $conn->exec($sql1);


if($int != 0){
    echo true;
}
