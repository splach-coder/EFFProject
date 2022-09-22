<?php

use JetBrains\PhpStorm\Internal\ReturnTypeContract;

function getSingleValue($conn, $sql){
    $q = $conn->prepare($sql);
    $q->execute();
    return $q->fetchColumn();
}

function datediff($conn, $created_at){
    $days = getSingleValue($conn, "SELECT (DATEDIFF(CURRENT_DATE(), '$created_at'))");
    if($days < 30){
        return $days . ' jours';
    }else{
        return intval($days/30.5) . ' mois';
    }
}



function graph($conn, $idannounce){
    $query = "SELECT concat(day(date), ' ', monthname(date)) as dt, COUNT(*) as c from `announce_views` WHERE id_announce = $idannounce group by day(date) order by date";
    $data = $conn->query($query);
    $final_arr = array();
    while ($dt=$data->fetch()){
        $arr = array(
            "date" => $dt["dt"],
            "views" => $dt["c"]
        );
        $final_arr [] = $arr;
    }
    //convert data to json format
    return json_encode($final_arr);
}

function visitorsGraphs($conn){
    $query = "SELECT concat(day(date), ' ', monthname(date)) as dt, COUNT(*) as c from `visitors-counter` group by day(date) order by date";
    $data = $conn->query($query);
    $final_arr = array();
    while ($dt=$data->fetch()){
        $arr = array(
            "date" => $dt["dt"],
            "views" => $dt["c"]
        );
        $final_arr [] = $arr;
    }
    //convert data to json format
    return json_encode($final_arr);
}

function visitorsGraphsPie($conn){
    $query = "SELECT city as dt , count(*) as c FROM `visitors-counter` GROUP BY city;";
    $data = $conn->query($query);
    $final_arr = array();
    while ($dt=$data->fetch()){
        $arr = array(
            "city" => $dt["dt"],
            "views" => $dt["c"]
        );
        $final_arr [] = $arr;
    }
    //convert data to json format
    return json_encode($final_arr);
}

function anceGraphsPie($conn){
    $query = "SELECT v.villename as city , count(*) as c FROM `announcer` a, `infos_generales` i, `ville` v
    WHERE a.id_infos_Generales = i.id_infos_Generales AND i.id_Ville = v.id_Ville
    GROUP BY city;";
    $data = $conn->query($query);
    $final_arr = array();
    while ($dt=$data->fetch()){
        $arr = array(
            "city" => $dt["city"],
            "announces" => $dt["c"]
        );
        $final_arr [] = $arr;
    }
    //convert data to json format
    return json_encode($final_arr);
}


function login($conn, $email, $pass, $name, $tel){
    #see if the user infos correct ot not
    $stmt = $conn->prepare("SELECT EXISTS(SELECT * FROM `users` u, `informations` i  WHERE u.id_infos = i.id AND (email = ? AND password = ?) AND i.full_name = ? AND i.telephone = ?) as RESULT;");
    $stmt->execute([$email, $pass, $name, $tel]);
    
    #get the table data
    $user = $stmt->fetch();
    
    $res = $user['RESULT'];
    
    if($res == 1){
        $userID = getSingleValue($conn, "SELECT id FROM `users` where email = '$email' AND password = '$pass'");
        return $userID;
    }
    else
        return false;
}

function getusers($conn, $params){
    $data = $conn->query("SELECT u.id, i.full_name, u.email, i.telephone, v.villename FROM `users` u, `informations` i, `ville` v
    WHERE u.id_infos = i.id
    AND i.id_Ville = v.id_Ville
    AND u.role = 'user'
    $params"); 
    $final_arr = array();
    while($dt = $data->fetch(PDO::FETCH_ASSOC)){
        $arr = array(
            "id" => $dt["id"],
            "name" => $dt["full_name"],
            "email" => $dt["email"],
            "tel" => $dt["telephone"],
            "ville" => $dt["villename"],
        );
        $final_arr [] = $arr;
    }

    return json_encode($final_arr);
}


function graphance($conn){
    $query = "SELECT concat(day(created_at), ' ', monthname(created_at)) AS dt, COUNT(*) as c from announcer GROUP BY day(created_at) ORDER BY created_at";
    $data = $conn->query($query);
    $final_arr = array();
    while ($dt=$data->fetch()){
        $arr = array(
            "date" => $dt["dt"],
            "announces" => $dt["c"]
        );
        $final_arr [] = $arr;
    }
    //convert data to json format
    return json_encode($final_arr);
}


function check($conn){
    try {
        $dat = date("Y-m-d h:i:s");
        $query = $conn->query("SELECT `id_announcer`, `end_in`, `id_user`, `status` FROM `announcer`");
        while($dt = $query->fetch(PDO::FETCH_ASSOC)){
            if($dat >= $dt["end_in"]){
                if($dt["status"] == 'active'){
                    $ida = $dt["id_announcer"];
                    $idu = $dt["id_user"];
                    $update = $conn->prepare("UPDATE `announcer` SET `status` = 'supprimer' WHERE id_announcer = $ida");
                    $nbr = $update->execute();
                    if($nbr != 0){
                        $sql1 = "INSERT INTO `msgfromadmin`(`to_user`, `msg`, `link`) VALUES ($idu, 'Votre annonce est supprimer for re-announce', 'gallery.php?id=$ida')";
                        $conn->exec($sql1);
                        return 'dazet';
                    }
                }
            }
        }

        return 'nn';
        
    } catch (PDOException $th) {
        return $th->getMessage();
    }
}
