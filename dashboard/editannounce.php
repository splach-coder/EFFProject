<?php
    session_start();
    require_once '../app/db.php';
    require '../assets/php/functions.php';

    $id = $_GET['id'];

    $sql = "SELECT a.id_announcer, i.id_infos_Generales, a.id_user, i.titre_annonce, i.prix, i.description, a.status FROM `infos_generales` i, `announcer` a, `images` im 
    WHERE i.id_infos_Generales = a.id_infos_Generales 
    AND i.id_infos_Generales = im.id_infos_Generales 
    AND a.id_announcer = $id
    GROUP BY a.id_announcer;";

    $stmt = $conn->query($sql);
    $ance = $stmt->fetch();

    $idinfo = $ance['id_infos_Generales'];

    $imagequery = "SELECT id_image, img FROM `images` WHERE id_infos_Generales = $idinfo";
    $stm2 = $conn->query($imagequery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!--links-->
    <link href="../assets/css/updateannounce.css" rel="stylesheet">
    <link href="css/man-style.css" rel="stylesheet">
    <link href="css/dss-bd.css" rel="stylesheet">
    <?php include 'links.php';?>


    <!--Link the Bootstrap-->
    <link rel="stylesheet" href="../node_modules\bootstrap\dist\css\bootstrap.min.css">

    <script src="../node_modules/bootstrap/dist/js/bootstrap.min.js"></script>

    <style>
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

    <!--Nav Bar-->
    <?php include 'sidemenu.php'?>

    <section class="home" style="margin-bottom: 50px;">
        <div class="text">Update Announce</div>
        <div class="container p-3 rounded d-flex flex-column shadow">
            <div class="row mb-2" style="width: 100%;">
                <div class="col d-flex justify-content-center">
                    <h4>Infos</h4>
                </div>
                <div class="col-5 d-flex justify-content-center">
                    <h4>Images</h4>
                </div>
            </div>
            <div class="row ms-2" style="width: 100%; height: 100%;">
                <div class="col ">
                    <div class="row mb-3" style="width: 100%;">
                        <?php $clr = ($ance['status'] == 'supprimer') ? '#FF5F56' : '#0191D8'?>
                        <label style="max-width: 25%;" for="titre">Status : <strong class="ms-1"
                                style="color: <?=$clr?>;"><?=$ance['status']?></strong></label>
                        <?php if($ance['status'] == 'supprimer'){?>
                        <button style="max-width: 100px;margin-left: auto;" class="btn btn-success" id="reance"
                            data-id-annonce="<?=$id?>">
                            <i class="fa-solid fa-arrow-rotate-left"></i>
                        </button>
                        <?php }?>
                    </div>

                    <div class="row mb-3">
                        <label for="titre">Titre</label>
                        <input type="text" id="titre" value="<?=$ance['titre_annonce']?>">
                    </div>

                    <div class="row mb-3">
                        <label for="titre">Prix</label>
                        <input type="number" id="prix" value="<?=$ance['prix']?>">
                    </div>

                    <div class="row">
                        <label for="titre">Description</label>
                        <textarea name="desc" id="desc" cols="10" rows="4"><?=$ance['description']?></textarea>
                    </div>
                </div>
                <div class="col-5 pt-2">
                    <div style="width: 100%; height: 100%;" class="d-flex flex-wrap justify-content-center images">
                        <?php while ($row = $stm2->fetch()) {?>
                        <div class="imageBox mb-3 me-3 d-flex flex-column justify-content-center align-items-center">
                            <img src="../assets/images/<?php echo $row['img'];?>" alt="">
                            <i class="fas fa-close icon-close" data-image="<?=$row['img'];?>"
                                id="<?=$row['id_image'];?>"></i>
                        </div>
                        <?php }?>

                        <div class="imageBox img156 me-3 d-flex flex-column justify-content-center align-items-center">
                            <form>
                                <label for="file"><i class="fa-solid fa-circle-plus"></i></label>
                                <input id="file" type="file" name="image" class="d-none">
                                <input type="submit" name="submit" id="sub" class="d-none" value="">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4 d-flex justify-content-center" style="width: 100%;">
                <button class="btn btn-primary py-2 me-5" id="btnsauve" style="width: 300px;">SAUVGARDER</button>
                <button class="btn btn-danger py-2" id="btnsupp" style="width: 300px;">SUPPRIMER</button>
            </div>
        </div>
    </section>


    <script>
    $(".icon-close").click(function() {
        const id = $(this).attr("id");
        const img = $(this).attr("data-image");

        $.post("../app/removeimg.php", {
            image: img,
            id: id
        }, (res) => {
            location.reload();
        });
    })


    $("form").submit(function(e) {
        e.preventDefault();

        var form = new FormData(this);

        $.ajax({
            type: "POST",
            url: "../app/handleimage.php?id=" + <?=$idinfo?>,
            data: form,
            processData: false, //add this
            contentType: false, //and this
            success: function(res) {
                location.reload();
            }
        })
    });

    $("#reance").click(function() {
        const id = $(this).attr("data-id-annonce");

        $.ajax({
            type: "POST",
            url: "back-end/reance.php",
            data: {
                id: id
            },
            success: function(res) {
                if (res == true)
                    $(location).prop("href", "announces.php")
            }
        })
    })

    $("#file").on("change", function() {
        $("#sub").click();
    })

    $("#btnsauve").click(function() {
        $.post("../app/updateannounce.php", {
                id_info: <?=$idinfo?>,
                title: $("#titre").val(),
                prix: $("#prix").val(),
                desc: $("#desc").val()
            },
            (data) => {
                if (data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated'
                    })
                }
            })
    })


    $("#btnsupp").click(function() {
        $.post("../app/traitannonce.php", {
                id: <?=$id?>,
                delete: 'delete'
            },
            (data) => {
                if (data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted'
                    })

                    setTimeout(function() {
                        history.back();
                    }, 1000)
                }
            })
    })
    </script>
</body>

</html>
