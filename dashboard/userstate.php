<?php 
    session_start();
    require_once '../app/db.php';
    include '../assets/php/functions.php';

    if(isset($_SESSION['user_id'])  &&  isset($_SESSION['user_id_infos'])  &&  isset($_SESSION['user_email']) && $_SESSION['role'] == 'admin'){

        $iduser = $_GET['id'];

        $username = getSingleValue($conn, " SELECT i.full_name FROM `users` u, `informations` i WHERE u.id_infos = i.id AND u.id = $iduser");
        
        $count = getSingleValue($conn, "SELECT COUNT(*) FROM `announcer` WHERE status = 'active' AND id_user =  $iduser ;"); 
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

    <!--Link the Bootstrap-->
    <link rel="stylesheet" href="../node_modules\bootstrap\dist\css\bootstrap.min.css">

    <script src="../node_modules/bootstrap/dist/js/bootstrap.min.js"></script>

    <style>
    .table .info {
        display: flex;
        align-items: center;
        border: none;
    }

    .table .info:hover {
        background-color: #ccc;
    }

    ol,
    ul {
        padding-left: 0 !important;
    }

    .nav-link {
        padding: 0 !important;
    }

    </style>
</head>

<body>
    <?php include 'sidemenu.php';?>

    <section class="home" style="margin-bottom: 50px;">
        <div class="text"><?=$username?></div>
        <div class="text ms-5 fs-4 text-dark">has <?=$count?> announces</div>
        <div class="table" style="width:90% !important; margin: auto;">
            <div class="header">
                <span style="max-width:10% !important;">Id</span>
                <span style=" width:50% !important;">Title</span>
                <span style=" max-width:15% !important;">Price</span>
                <span style=" max-width:15% !important;">Views</span>
                <span style=" max-width:10% !important;">status</span>
            </div>
            <?php  $data = $conn->query("SELECT a.id_announcer, inf.titre_annonce, inf.prix, a.status FROM `announcer` a, `infos_generales` inf, `users` u
                        WHERE a.id_infos_Generales = inf.id_infos_Generales 
                        AND a.id_user = u.id
                        AND u.id = $iduser
                        ORDER BY a.id_announcer DESC"); ?>
            <?php  while($dt = $data->fetch(PDO::FETCH_ASSOC)){?>
            <div class="info" data-id-ance="<?=$dt['id_announcer']?>">
                <?php $ance =  $dt['id_announcer'];
                            $viewss = getSingleValue($conn, "SELECT count(*) FROM `announce_views` WHERE id_announce = $ance GROUP BY id_announce");
                            if(empty($viewss)){
                                $viewss = 0;
                            }
                            ?>
                <span style=" max-width:10% !important;">
                    <?php echo $ance?>
                </span>
                <span style="width:50% !important;"><?php echo $dt['titre_annonce']?></span>
                <span style=" max-width:15% !important;"> <?php echo $dt['prix']?></span>
                <span style=" max-width:15% !important;"><?php echo $viewss ?>
                </span>
                <span style=" max-width:10% !important;"><?php echo $dt['status']?></span>
            </div>
            <?php }?>
        </div>
    </section>
    <script>
    $(document).ready(function() {
        $(".info").click(function() {
            const id = $(this).attr("data-id-ance");
            $(location).prop('href', 'editannounce.php?id=' + id);
        })
    })
    </script>
</body>

</html>
<?php }else{
    header("Location: ../firstpage.php");
}?>
