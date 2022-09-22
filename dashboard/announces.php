<?php 
    session_start();
    require_once '../app/db.php';
    include '../assets/php/functions.php';

    if(isset($_SESSION['user_id'])  &&  isset($_SESSION['user_id_infos'])  &&  isset($_SESSION['user_email']) && $_SESSION['role'] == 'admin'){
  
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/man-style.css" rel="stylesheet">
    <link href="css/dss-bd.css" rel="stylesheet">

    <?php include 'links.php';?>
</head>

<body>
    <?php include 'sidemenu.php';?>

    <section class="home" style="margin-bottom: 50px;">
        <div class=" text">Announces</div>
        <div class="table" style="width:90% !important; margin: auto;">
            <div class="header">
                <span style="max-width:10% !important;">Id</span>
                <span style=" max-width:50% !important;">Title</span>
                <span style=" max-width:15% !important;">Price</span>
                <span style=" max-width:15% !important;">Views</span>
                <span style=" max-width:15% !important;">From</span>
                <span style=" max-width:15% !important;">status</span>
                <span style=" max-width:5% !important;"></span>
            </div>
            <?php  $data = $conn->query("SELECT a.id_announcer, inf.titre_annonce, inf.prix, i.full_name, a.status FROM `announcer` a, `infos_generales` inf, `users` u, `informations` i 
                        WHERE a.id_infos_Generales = inf.id_infos_Generales 
                        AND a.id_user = u.id
                        AND u.id_infos = i.id
                        ORDER BY a.id_announcer DESC"); ?>
            <?php  while($dt = $data->fetch(PDO::FETCH_ASSOC)){?>
            <div class="info">
                <?php $ance =  $dt['id_announcer'];
                            $viewss = getSingleValue($conn, "SELECT count(*) FROM `announce_views` WHERE id_announce = $ance GROUP BY id_announce");
                            if(empty($viewss)){
                                $viewss = 0;
                            }
                            ?>
                <span style=" max-width:10% !important;">
                    <?php echo $ance?>
                </span>
                <span style=" max-width:50% !important;"><?php echo $dt['titre_annonce']?></span>
                <span style=" max-width:15% !important;"> <?php echo $dt['prix']?></span>
                <span style=" max-width:15% !important;"><?php echo $viewss ?>
                </span>
                <span style=" max-width:15% !important;"><?php echo $dt['full_name']?></span>
                <span style=" max-width:15% !important;"><?php echo $dt['status']?></span>
                <span style=" max-width:5% !important;"><a href="editannounce.php?id=<?=$ance?>"
                        style="padding:5px 10px; text-decoration: none; background: #6C63FF; color: #f5f5f5; border-radius: 7px;">view</a></span>
            </div>
            <?php }?>
        </div>
    </section>
    <script>

    </script>
</body>

</html>
<?php }else{
    header("Location: ../firstpage.php");
}?>
