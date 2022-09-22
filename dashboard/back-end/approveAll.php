<?php 
session_start();
require_once '../../app/db.php';

$sql1 = "UPDATE `announcer` SET status = 'active' where status = 'moderation'";
$int = $conn->exec($sql1);

if($int != 0){
    echo true;
}else
    echo false;
