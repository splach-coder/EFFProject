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

    <style>
    .filter123 {
        width: 90%;
        margin: auto;
        display: flex;
        justify-content: flex-end;
        height: 45px;
        position: relative;
    }

    .select-menu {
        margin: 0;
    }

    .select-btn {
        padding: 0 !important;
        border: none !important;
        height: 45px !important;
        display: flex;
        justify-content: space-around !important;
        min-width: 150px;
    }

    </style>
</head>

<body>
    <?php include 'sidemenu.php';?>

    <section class="home" style="margin-bottom: 50px;">
        <div class=" text">Visitors</div>
        <div class="filter123">
            <div class="select-menu">
                <div class="select-btn">
                    <span class="option-text">Filter</span>
                    <i class="fa-solid fa-filter" id="filter32"></i>
                </div>
                <ul class="options">
                    <li class="option" data-type="30">
                        <i class="fa-solid fa-minus" style="color: #171515;"></i>
                        <span class="option-text">limit 30</span>
                    </li>
                    <li class="option" data-type="all">
                        <i class="fa-solid fa-plus" style="color: #171515;"></i>
                        <span class="option-text">show all</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="table" style="width:90% !important; margin: auto;">
            <div class="header">
                <span style="width:30% !important;">Date</span>
                <span style="width:40% !important;">Adress Ip</span>
                <span style="width:30% !important;">City</span>
            </div>
            <?php  $data = $conn->query("SELECT `date`, `ip`, `city` FROM `visitors-counter`  ORDER by id DESC LIMIT 30"); ?>
            <?php  while($dt = $data->fetch(PDO::FETCH_ASSOC)){?>
            <div class="info">
                <span style="width:30% !important;"><?=$dt['date']?></span>
                <span style="width:40% !important;"><?=$dt['ip']?> </span>
                <span style="width:30% !important;"><?=$dt['city']?></span>
            </div>
            <?php }?>
        </div>
    </section>
    <script>
    $(".option").click(function() {
        const type = $(this).attr("data-type");
        filter(type);
    })

    function filter(type) {
        if (type == "all") {
            $(".table").empty();
            $(".table").append(`
            <div class="header">
                <span style="width:30% !important;">Date</span>
                <span style="width:40% !important;">Adress Ip</span>
                <span style="width:30% !important;">City</span>
            </div>
            <?php  $data = $conn->query("SELECT `date`, `ip`, `city` FROM `visitors-counter`  ORDER by id DESC"); ?>
            <?php  while($dt = $data->fetch(PDO::FETCH_ASSOC)){?>
            <div class="info">
                <span style="width:30% !important;"><?=$dt['date']?></span>
                <span style="width:40% !important;"><?=$dt['ip']?> </span>
                <span style="width:30% !important;"><?=$dt['city']?></span>
            </div>
            <?php }?>
            `);
        } else {
            $(".table").empty();
            $(".table").append(`
            <div class="header">
                <span style="width:30% !important;">Date</span>
                <span style="width:40% !important;">Adress Ip</span>
                <span style="width:30% !important;">City</span>
            </div>
            <?php  $data = $conn->query("SELECT `date`, `ip`, `city` FROM `visitors-counter`  ORDER by id DESC LIMIT 30"); ?>
            <?php  while($dt = $data->fetch(PDO::FETCH_ASSOC)){?>
            <div class="info">
                <span style="width:30% !important;"><?=$dt['date']?></span>
                <span style="width:40% !important;"><?=$dt['ip']?> </span>
                <span style="width:30% !important;"><?=$dt['city']?></span>
            </div>
            <?php }?>
            `);
        }

    }
    </script>
</body>

</html>
<?php }else{
    header("Location: ../firstpage.php");
}?>
