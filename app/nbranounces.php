<?php
 require_once 'db.php';
 require '../assets/php/functions.php';

  $id = $_POST['id'];
  
  $number  = getSingleValue($conn, "SELECT COUNT(*) FROM `announcer` a, `infos_generales` i WHERE a.id_infos_Generales = i.id_infos_Generales AND status = 'active' AND i.id_Cat = $id");
  echo $number;
