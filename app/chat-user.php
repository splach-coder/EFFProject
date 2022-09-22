<?php 
session_start();
include_once 'db.php';

$buyer = $_SESSION['user_id'];
$another = $_POST['chat'];
$vente = $_POST['vente'];

$switch = ($vente == 'achat') ? 'not' : '';

$data = $conn->query("SELECT u.id, full_name, picture, a.id_announcer, inf.titre_annonce FROM `users` u,  `informations` i, `announcer` a, `infos_generales` inf
WHERE u.id_infos = i.id
AND inf.id_infos_Generales = a.id_infos_Generales
AND a.id_announcer $switch in (SELECT `id_announcer` from `announcer` where id_user = $buyer)
AND (u.id IN (SELECT from_user FROM `chat_table` WHERE id_announce = a.id_announcer and to_user = $buyer)
OR $buyer IN (SELECT from_user FROM `chat_table` WHERE id_announce = a.id_announcer and to_user = u.id))"); 

                    
while($dt = $data->fetch(PDO::FETCH_ASSOC)){
    $id = $dt['id'];
    $id_announce = $dt['id_announcer'];
    
    $query = "SELECT `from_user`, `msg`, DATE_FORMAT(created_at, '%H:%i') as 'time' FROM `chat_table` WHERE ((`from_user` = $buyer AND `to_user` = $id) OR
    (`from_user` = $id OR `to_user` = $buyer))
    AND id_announce = $id_announce
    ORDER BY created_at desc LIMIT 1";
    
    $alt = $conn->query($query);
    $msg = "";
    $time = "";
    
    while($dtt = $alt->fetch(PDO::FETCH_ASSOC)){
        $msg = (($dtt['from_user'] == $buyer) ? 'YOU : ' : '') . $dtt['msg'];
        $time = $dtt['time'];
    }
                
    echo "<div class='chat' data-id-user='$id' data-id-announce='$id_announce'> 
            <div class='imgBx'>
                <img src='assets/images/".$dt['picture']."' alt=''>
            </div>
            
            <div class='details'>
                <div class='head'>
                    <h4 class='name'>".$dt['full_name']." | <a href='http://localhost/project/gallery.php?id=$id_announce'>".$dt['titre_annonce']."</a></h4>
                    <span class='time'>".$time."</span>  
                </div>
            
                <div class='msgs'>
                    <p class='msg'>".$msg."</p>
                    <b class='num'></b>
                </div>
            </div>
        </div>";
}
