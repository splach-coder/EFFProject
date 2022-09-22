<?php
session_start();
include_once 'db.php';

$sender = $_SESSION['user_id'];
$ance = $_POST['announce'];
$reciever = $_POST['chat'];
$msg = $_POST['msg'];

$sql1 = "INSERT INTO `chat_table`(`msg`, `from_user`, `to_user`, `id_announce`) VALUES (?,?,?,?);";

$stmt = $conn->prepare($sql1);

$stmt->execute([$msg, $sender, $reciever, $ance]);

try{
    $data = $conn->query("SELECT  `from_user`, `to_user`, `msg`, DATE_FORMAT(created_at, '%H:%i') as 'time' FROM `chat_table` 
    WHERE ((`from_user` = $sender AND `to_user` = $reciever) 
    OR (`from_user` = $reciever AND `to_user` = $sender)) AND `id_announce` = $ance
    ORDER BY created_at asc;");


    while($dt = $data->fetch(PDO::FETCH_ASSOC)){
        $m = $dt['msg'];
        $f = $dt['from_user'];
        $time = $dt['time'];
        $who = ($f == $sender) ? 'msg-me' : 'msg-frnd';
        
        echo '<div class="msg ' . $who . '">';
        echo '<p> ' . $m . ' <br> <span>'.$time.'</span></p>';
        echo '</div>';
    }

}catch(PDOException $e){
    echo $e->getMessage();
}
