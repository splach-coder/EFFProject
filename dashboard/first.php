<?php 
    session_start();
    require_once '../app/db.php';
    include '../assets/php/functions.php';

    if(isset($_SESSION['user_id'])  &&  isset($_SESSION['user_id_infos'])  &&  isset($_SESSION['user_email']) && $_SESSION['role'] == 'admin'){

        $adminName = getSingleValue($conn, "SELECT full_name FROM `informations` WHERE `id` = " . $_SESSION['user_id_infos']);

        $at = getSingleValue($conn, "SELECT accept_time FROM `params`");
        $at = $at/1000/60;

        $catid = $_GET['cat'];
        $params = "";

        if($catid != -1){
            $params = "AND i.id_Cat = $catid";
        }
        $name = "order by category";
        if(isset($_GET['name'])){
            $name = $_GET['name'];
        }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/man-style.css" rel="stylesheet">

    <?php include 'links.php';?>
</head>

<body>
    <?php include 'sidemenu.php';?>

    <section class="home" style="margin-bottom: 50px;">
        <div class="text">Welcome <?php echo $adminName?></div>
        <div class="searches">
            <div class="cats">
                <div class="select-menu">
                    <div class="select-btn">
                        <span class="sBtn-text"><?php echo $name?></span>
                        <i class="bx bx-chevron-down"></i>
                    </div>
                    <ul class="options">
                        <li class="option" data-cat-id="-1">
                            <span class="option-text">All</span>
                        </li>
                        <?php  $data = $conn->query("SELECT * FROM `categories`"); ?>
                        <?php  while($dt = $data->fetch(PDO::FETCH_ASSOC)){?>
                        <li class="option" data-cat-id="<?php echo $dt['id_Cat']?>">
                            <span class="option-text"><?php echo $dt['Name_Cat']?></span>
                        </li>
                        <?php }?>
                    </ul>
                </div>

                <button type="button" id="accept" style="margin-bottom: 15px; border: .5px solid #ccc; padding: 15px; border-radius:15px;font-size: 18px; font-family: sans-serif;cursor: pointer;border-radius: 6px;
background: #E4E9F7;
box-shadow:  5px 5px 18px #d6dbe8,
             -5px -5px 18px #f2f7ff;color: #333;">Accept
                    all</button>

                <span style="margin-left: 10px;">Approve Time : <?=$at/1000/60?> min</span>
            </div>
        </div>
        <div class=" con">
            <?php $query = "SELECT a.id_announcer, a.id_user, i.titre_annonce, i.prix, i.etat, im.img, CONCAT(DAY(a.created_at), ' ', MONTHNAME(a.created_at)) as date, inf.full_name
            FROM `infos_generales` i, `announcer` a, `images` im, `users` u, `informations` inf 
            WHERE i.id_infos_Generales = a.id_infos_Generales 
            AND i.id_infos_Generales = im.id_infos_Generales
            AND a.id_user = u.id
            AND u.id_infos = inf.id
            AND a.status = 'moderation' $params GROUP BY a.id_announcer;";?>
            <?php  $data = $conn->query($query); ?>
            <?php  while($dt = $data->fetch(PDO::FETCH_ASSOC)){?>
            <div class="wrapper" data-id-announce="<?php echo $dt['id_announcer']?>"
                data-id-user="<?php echo $dt['id_user']?>">
                <div class="container">
                    <div class="top" style="background: url('../assets/images/<?php echo $dt["img"]?>') no-repeat center center;
                        background-size: cover;">
                    </div>
                    <div class="bottom">
                        <div class="left">
                            <div class="details">
                                <h1><?php echo $dt['titre_annonce']?></h1>
                                <p><?php echo $dt['prix']?> DH</p>
                            </div>
                            <div class="buy"><i class="fa-solid fa-check"></i></div>
                        </div>
                        <div class="right">
                            <div class="done"><i class="material-icons">done</i></div>
                            <div class="details">
                                <h1><?php echo $dt['titre_annonce']?></h1>
                                <p>Added to your cart</p>
                            </div>
                            <div class="remove"><i class="material-icons">clear</i></div>
                        </div>
                    </div>
                </div>
                <div class="inside">
                    <div class="icon"><i class="material-icons">info_outline</i></div>
                    <div class="contents">
                        <table>
                            <tr>
                                <th>By :</th>
                            </tr>
                            <tr>
                                <td> <?php echo $dt['full_name'] ?></td>
                            </tr>
                            <tr>
                                <th>Created_at :</th>
                            </tr>
                            <tr>
                                <td><?php echo $dt['date'] ?></td>
                            </tr>
                            <tr>
                                <th>Etat</th>
                            </tr>
                            <tr>
                                <td><?php echo $dt['etat'] ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <?php }?>
        </div>
    </section>
    <script>
    let id_cat = -1;
    $(".option").click(function() {
        let name;
        if ($(this).parent().parent().parent().hasClass("cats")) {
            id_cat = $(this).attr("data-cat-id");
            name = $(this).text()
        }


        $(location).attr('href', "first.php?cat=" + id_cat + "&name=" + name);
    })
    let timer;
    $(".buy").click(function() {
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'article will be approved after <?=$at?> min',
            showConfirmButton: false,
            timer: 1100
        })

        const id = $(this).parent().parent().parent().parent().attr("data-id-announce");
        const id_user = $(this).parent().parent().parent().parent().attr("data-id-user");
        timer = setTimeout(function() {
            $.ajax({
                type: "POST",
                url: 'back-end/approve.php',
                data: {
                    id: id,
                    user: id_user
                },
                success: function(response) {
                    if (response == true)
                        location.reload();
                }
            });
        }, <?=$at?>)
    })

    $("#accept").click(function() {
        $.ajax({
            type: "POST",
            url: 'back-end/approveAll.php',
            data: {},
            success: function(response) {
                if (response == true)
                    location.reload();
            }
        });
    })

    $(".remove").click(function() {
        clearTimeout(timer);
        Swal.fire({
            position: 'center',
            icon: 'info',
            title: 'article still not approved',
            showConfirmButton: false,
            timer: 1100
        })
    })
    </script>
</body>

</html>
<?php }else{
    header("Location: ../firstpage.php");
}?>
