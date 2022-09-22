<?php
    session_start();
    require_once 'db.php';
    $idUser = $_SESSION['user_id'];
    if(isset($_POST['type']) && isset($_POST['id']) && $_POST['type'] == 'seen'){
        $id = $_POST['id'];
        $sql1 = "UPDATE `msgfromadmin` SET `seen`= 1 WHERE `id` = ?";
        $stmt = $conn->prepare($sql1);
        $stmt->execute([$id]);

        echo true;
    }else{
    $type = "";
    if(isset($_POST['type']) && $_POST['type'] != 'all'){
        $type = " AND seen = 0";
    }
    
    $msgs = 'SELECT id, msg, DATE_FORMAT(created_at, "%H:%i") as tm, seen FROM `msgfromadmin` WHERE to_user = '. $idUser .$type . ' ORDER BY id DESC';
    $data = $conn->query($msgs);
    
    while($dt = $data->fetch(PDO::FETCH_ASSOC)){
        echo '<li>
        <div class="all">
            <div class="li_left">
                <img src="assets/icons/logo.png" alt="">
            </div>
            <div class="li_right" data-id="'.$dt['id'].'">
                <div class="message">
                    <div class="title" style="font-size: 18px;">Crowed Shopee</div>
                    <div class="sub_title">
                        '.$dt['msg'].'
                    </div>
                </div>
                <div class="time_status">
                    <div class="time"> '.$dt['tm'].'</div>';
                if($dt['seen'] == 0){
                echo '    <div class="status">
                        <i class="fas fa-envelope"></i>
                    </div>';
                }
                echo '
                </div>
            </div>
        </div>
    </li>';
    }}
