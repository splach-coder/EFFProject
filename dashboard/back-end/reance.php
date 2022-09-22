<?php 
session_start();
require_once '../../app/db.php';

$id = $_POST['id'];

$sql1 = "UPDATE `announcer` SET status = 'active' where id_announcer = $id";
$int = $conn->exec($sql1);

if($int != 0){
    echo true;
}
