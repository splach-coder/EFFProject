<?php 
    session_start();
    require_once 'app/db.php';
    include 'assets/php/functions.php';
    $userId = $_SESSION['user_id'];
    $params = " AND a.id_user = $userId ";
    $active = getSingleValue($conn, "SELECT count(*) FROM `announcer` a, `infos_generales` i WHERE a.id_infos_Generales = i.id_infos_Generales AND id_user = $userId AND status = 'active'");
    $rejected = getSingleValue($conn, "SELECT count(*) FROM `announcer` a, `infos_generales` i WHERE a.id_infos_Generales = i.id_infos_Generales AND id_user = $userId AND status = 'rejected'");
    $moderation = getSingleValue($conn, "SELECT count(*) FROM `announcer` a, `infos_generales` i WHERE a.id_infos_Generales = i.id_infos_Generales AND id_user = $userId AND status = 'moderation'");
    $supprimer = getSingleValue($conn, "SELECT count(*) FROM `announcer` a, `infos_generales` i WHERE a.id_infos_Generales = i.id_infos_Generales AND id_user = $userId AND status = 'supprimer'");


    $stmt = $conn->prepare("SELECT i.full_name, i.telephone, u.email, i.id_Ville FROM `users` u, `informations` i
    WHERE u.id_infos = i.id 
    AND u.id = ?");

    $stmt->execute([$userId]); 
    
    $user = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>account</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--Links-->
    <?php include_once 'envirmentLinks.php'?>

    <!--Private Links-->
    <link rel="stylesheet" href="assets/css/firstPageStyle.css">
    <link rel="stylesheet" href="assets/css/cards.css">
    <link rel="stylesheet" href="assets/css/modal.css">
    <link rel="stylesheet" href="assets/css/account.css">
    <link href="https://fonts.googleapis.com/css?family=Medula+One" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,500;1,400&display=swap" rel="stylesheet">

    <script defer src="assets/javascript/Account.js"> </script>

    <style>
    @import url('https://fonts.googleapis.com/css2?family=Rubik&display=swap');

    .rty {
        font-family: 'Rubik', sans-serif;
        font-size: 16px;
        line-height: 24px;
        color: #666666;
        transition: all 150ms ease-in;
        background-color: #E9E9E9;
    }

    .rty:hover {
        color: #2e6bff;
        background-color: #D0D0D0;
    }

    .rty.active {
        color: #2e6bff;
    }

    .form {
        width: 80%;
        font-family: 'Rubik', sans-serif;
    }


    .row input,
    .row select {
        padding: 10px;
    }

    .row input:hover,
    .row select:hover {
        border: 1px solid black;
    }

    .row.err input,
    .row.err select {
        border: 2px solid #D13649;
    }

    .row.err input:hover,
    .row.err select:hover {
        border: 2px solid #c6192d;
    }

    .row span.err {
        display: none;
    }

    .row.err span.err {
        display: block;
        color: #D13649;
    }

    .faq {
        font-size: 14px;
    }

    .faq a {
        text-decoration: none;
    }

    .description {
        font-size: 16px;
    }

    .star {
        color: red;
    }

    #submit:disabled {
        background-color: #96B5FF;
        outline: none;
        border: none;
    }

    #sauvepass:disabled {
        background-color: #96B5FF;
        outline: none;
        border: none;
    }

    </style>
</head>

<body>
    <!--Nav Bar-->
    <?php include_once 'normalNavbar.php'?>

    <div class="container my-5">
        <div class="row">
            <div class="navs d-flex">
                <div class="nav-Item active one">
                    <span class="nav-icon "><i class="fa-solid fa-list-check"></i></span>
                    <button class="nav-btn">Mes annonces</button>
                </div>
                <div class="nav-Item two">
                    <span class="nav-icon"><i class="fa-regular fa-heart"></i></span>
                    <button class="nav-btn">Mes Favoris</button>
                </div>
                <div class="nav-Item three">
                    <span class="nav-icon"><i class="fa-solid fa-gear"></i></span>
                    <button class="nav-btn">Réglages</button>
                </div>
            </div>
        </div>
        <div class="row show-row mt-4 ">
            <div class="row">
                <div class="col status">
                    <h4 class="mt-4">Status</h4>
                    <ul>
                        <li><label class="form-control">
                                <input type="radio" name="radio" id="active" checked />
                                Actives (<?php echo $active?>)
                            </label></li>
                        <li><label class="form-control">
                                <input type="radio" name="radio" id="moderation" />
                                Dans la modération (<?=$moderation?>)
                            </label></li>
                        <li><label class="form-control">
                                <input type="radio" name="radio" id="rejete" />
                                Rejetées (<?=$rejected?>)
                            </label></li>
                        <li><label class="form-control">
                                <input type="radio" name="radio" id="supprimer" />
                                Supprimées (<?=$supprimer?>)
                            </label></li>
                    </ul>
                </div>
                <div class="col-8 lkj col1">
                    <div class="demo">
                        <div class="mainCard">
                            <div class="mainCardContent page-wrapper pp45">
                                <?php  $data = $conn->query("SELECT a.id_announcer, a.id_user, i.titre_annonce, i.prix, i.etat, im.img, a.created_at FROM `infos_generales` i, `announcer` a, `images` im WHERE i.id_infos_Generales = a.id_infos_Generales and i.id_infos_Generales = im.id_infos_Generales $params AND status = 'active'  GROUP BY a.id_announcer;"); ?>
                                <?php  while($dt = $data->fetch(PDO::FETCH_ASSOC)){?>
                                <div class="page-inner miniCard" data-id-announce="<?php echo $dt['id_announcer']?>"
                                    data-id-user="<?php echo $dt['id_user']?>">
                                    <div class="el-wrapper">
                                        <div class="box-up">
                                            <img class="img" src="assets/images/<?php echo $dt['img']?>" alt="image">
                                            <div class="img-info">
                                                <div class="info-inner text-center">
                                                    <span class="p-name fw-bold m-auto p-1"
                                                        style="border-radius: 15px; background: #f5f5f5; width: fit-content;">
                                                        <?php echo $dt['titre_annonce']?>
                                                    </span>
                                                    <span class="p-company text-lowercase"><i
                                                            class="fa-solid fa-clock"></i>&nbsp;&nbsp;<?php echo 'il y a ' . datediff($conn, $dt['created_at'])?></span>
                                                </div>
                                                <div class="a-size">Etat : <span class="size">
                                                        <?php echo $dt['etat']?></span></div>
                                            </div>
                                        </div>

                                        <div class="box-down">

                                            <div class="h-bg">
                                                <div class="h-bg-inner"></div>
                                            </div>

                                            <a class="cart" href="#">
                                                <span class="price"><?php echo $dt['prix'] . ' DH'?></span>
                                                <span class="add-to-cart">
                                                    <span class="txt "
                                                        onclick="window.location.href='gallery.php?id=<?php echo $dt['id_announcer']?>'">Regarder</span>
                                                </span>
                                            </a>
                                        </div>
                                    </div>

                                </div>
                                <?php }?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col lkj d-none col2">
                    <div class="demo">
                        <div class="mainCard">
                            <div class="mainCardContent page-wrapper">
                                <?php  $data = $conn->query("SELECT a.id_announcer, a.id_user, i.titre_annonce, i.prix, i.etat, im.img, a.created_at FROM `infos_generales` i, `announcer` a, `images` im, `favories` f WHERE i.id_infos_Generales = a.id_infos_Generales and i.id_infos_Generales = im.id_infos_Generales AND a.id_announcer = f.id_annonce AND status = 'active' and f.id_user = $userId  GROUP BY a.id_announcer;"); ?>
                                <?php  while($dt = $data->fetch(PDO::FETCH_ASSOC)){?>
                                <div class="page-inner miniCard" data-id-announce="<?php echo $dt['id_announcer']?>"
                                    data-id-user="<?php echo $dt['id_user']?>">
                                    <div class="el-wrapper">
                                        <div class="box-up">
                                            <div class="icon d-flex justify-content-end fs-5" style="cursor: pointer;">
                                                <i class="fas fa-close"></i>
                                            </div>
                                            <img class="img" src="assets/images/<?php echo $dt['img']?>" alt="image">
                                            <div class="img-info">
                                                <div class="info-inner text-center">
                                                    <span class="p-name fw-bold m-auto p-1"
                                                        style="border-radius: 15px; background: #f5f5f5; width: fit-content;">
                                                        <?php echo $dt['titre_annonce']?>
                                                    </span>
                                                    <span class="p-company text-lowercase"><i
                                                            class="fa-solid fa-clock"></i>&nbsp;&nbsp;<?php echo 'il y a ' . datediff($conn, $dt['created_at'])?></span>
                                                </div>
                                                <div class="a-size">Etat : <span class="size">
                                                        <?php echo $dt['etat']?></span></div>
                                            </div>
                                        </div>

                                        <div class="box-down">
                                            <div class="h-bg">
                                                <div class="h-bg-inner"></div>
                                            </div>

                                            <a class="cart" href="#">
                                                <span class="price"><?php echo $dt['prix'] . ' DH'?></span>
                                                <span class="add-to-cart">
                                                    <span class="txt "
                                                        onclick="window.location.href='gallery.php?id=<?php echo $dt['id_announcer']?>'">Regarder</span>
                                                </span>
                                            </a>
                                        </div>
                                    </div>

                                </div>
                                <?php }?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col lkj d-none  col3">
                    <div class="row " style="width: 100%; height: 100%; color: #666666;">
                        <div class="col-3 pt-3 d-flex flex-column align-items-center ">
                            <div class="p-3 row rty active infos" style=" width: 95%; cursor: pointer;">
                                <div class="col ">Modifier vos informations</div>
                                <div class="col-1 b">
                                    <i class="fa-solid fa-chevron-right"></i>
                                </div>
                            </div>
                            <div class="p-3 row mt-3 rty  pass" style=" width: 95%; cursor: pointer;">
                                <div class="col">Modifier le mot de passe</div>
                                <div class="col-1 ">
                                    <i class="fa-solid fa-chevron-right"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col colinfo d-flex justify-content-center">
                            <form class="form mt-3">
                                <div class="row mb-4">
                                    <label for="exampleFormControlInput3" class="form-label"> <span
                                            class="star">*</span>
                                        Nom</label>
                                    <input type="text" class="form-control name pppi" id="exampleFormControlInput3"
                                        placeholder="John Doe" name="S_fullname" value="<?=$user['full_name']?>">
                                    <span class="err"><i class="fa-solid fa-circle-exclamation"></i> Veuillez renseigner
                                        ce champ</span>
                                </div>
                                <div class="row mb-4">
                                    <label for="exampleFormControlInput4" class="form-label"> <span
                                            class="star">*</span>
                                        Téléphone</label>
                                    <input type="text" class="form-control tel pppi" id="exampleFormControlInput4"
                                        placeholder="06XXXXXXXX" name="S_tel" value="<?=$user['telephone']?>">
                                    <span class="err"><i class="fa-solid fa-circle-exclamation"></i> Veuillez renseigner
                                        ce
                                        champ</span>
                                </div>
                                <div class="row mb-4">
                                    <label for="exampleFormControlInput1" class="form-label"> <span
                                            class="star">*</span> E-mail
                                        address</label>
                                    <input type="email" class="form-control" id="exampleFormControlInput1"
                                        placeholder="name@example.com" name="S_email" disabled
                                        value="<?=$user['email']?>">
                                    <p class="text-muted" style="font-size: 14px; line-height: 21px;">Pour des raisons
                                        de
                                        confidentialité,
                                        l’adresse mail ne peut
                                        pas être
                                        modifiée,
                                        pour plus d’information veuillez contacter notre service client au Tél:
                                        05-20-42-86-86</p>
                                </div>

                                <div class="row mb-4">
                                    <label for="exampleFormControlInput5" class="form-label"> <span
                                            class="star">*</span> Ville</label>
                                    <select class="form-select form-control" id="cities"
                                        aria-label="Default select example" name="cities">
                                        <?php  $data = $conn->query("SELECT * FROM `ville`"); ?>
                                        <?php  while($dt = $data->fetch(PDO::FETCH_ASSOC)){?>
                                        <option value="<?php echo $dt['id_Ville']?>"
                                            <?php if($user['id_Ville'] == $dt['id_Ville']) echo 'selected'?>>
                                            <?php echo $dt['villename']?>
                                        </option>
                                        <?php }?>
                                    </select>
                                    <span class="err"><i class="fa-solid fa-circle-exclamation"></i> Veuillez renseigner
                                        ce champ</span>
                                </div>
                                <div class="row ">
                                    <button style="width: 230px; height: 45px;" class="btn btn-primary ms-auto"
                                        type="button" id="submit" name="submit" disabled>
                                        SAUVEGARDER
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="col d-none colpass d-flex justify-content-center">
                            <form class="form mt-3">
                                <div class="row mb-4">
                                    <label for="exampleFormControlInput4" class="form-label"> <span
                                            class="star">*</span>
                                        Mot de passe actuel</label>
                                    <input type="text" class="form-control ppps actuel" id="exampleFormControlInput4"
                                        placeholder="Mot de passe actuel">
                                    <span class=" err"><i class="fa-solid fa-circle-exclamation"></i> Veuillez
                                        renseigner
                                        ce
                                        champ</span>
                                </div>

                                <div class="row mb-4">
                                    <label for="exampleFormControlInput4" class="form-label"> <span
                                            class="star">*</span>
                                        Nouveau mot de passe</label>
                                    <input type="text" class="form-control ppps new" id="exampleFormControlInput4"
                                        placeholder="Nouveau mot de passe">
                                    <span class="err"><i class="fa-solid fa-circle-exclamation"></i> Veuillez renseigner
                                        ce
                                        champ</span>
                                </div>

                                <div class="row mb-4">
                                    <label for="exampleFormControlInput4" class="form-label"> <span
                                            class="star">*</span>
                                        Confirmer le mot de passe</label>
                                    <input type="text" class="form-control  ppps renew" id="exampleFormControlInput4"
                                        placeholder="Confirmer le mot de passe">
                                    <span class="err"><i class="fa-solid fa-circle-exclamation"></i> Veuillez renseigner
                                        ce
                                        champ</span>
                                </div>

                                <div class="row">
                                    <button type="button" id="sauvepass"
                                        style="width: 230px; height: 45px; cursor: pointer;"
                                        class="btn btn-primary ms-auto" disabled>SAUVEGARDER</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--footer-->
    <?php include 'footer.php'?>
    <!--end footer-->

    <!-- modal -->
    <div class="modal-overlay">
        <div class="modal">

            <a class="close-modal">
                <svg viewBox="0 0 20 20">
                    <path fill="#000000"
                        d="M15.898,4.045c-0.271-0.272-0.713-0.272-0.986,0l-4.71,4.711L5.493,4.045c-0.272-0.272-0.714-0.272-0.986,0s-0.272,0.714,0,0.986l4.709,4.711l-4.71,4.711c-0.272,0.271-0.272,0.713,0,0.986c0.136,0.136,0.314,0.203,0.492,0.203c0.179,0,0.357-0.067,0.493-0.203l4.711-4.711l4.71,4.711c0.137,0.136,0.314,0.203,0.494,0.203c0.178,0,0.355-0.067,0.492-0.203c0.273-0.273,0.273-0.715,0-0.986l-4.711-4.711l4.711-4.711C16.172,4.759,16.172,4.317,15.898,4.045z">
                    </path>
                </svg>
            </a><!-- close modal -->

            <div class="modal-content border-0">
                <div class="container">
                    <div class="row d-flex justify-content-center">
                        CHOISISSEZ UNE CATÉGORIE
                    </div>

                    <?php  $data = $conn->query("SELECT * FROM `categories`"); ?>
                    <?php  while($dt = $data->fetch(PDO::FETCH_ASSOC)){?>
                    <div class="row category" data-cat-id="<?php echo $dt['id_Cat']?>">
                        <div class="imageBx">
                            <img src="assets/icons/<?php echo $dt['icon']?>" alt="">
                        </div>
                        <div class="title">
                            <?php echo $dt['Name_Cat']?>
                        </div>
                    </div>
                    <?php }?>
                </div>
            </div><!-- content -->

        </div><!-- modal -->
    </div><!-- overlay -->


    <div class="modall">
        <div class="modall-content">
            <div class="d-flex justify-content-end"><span class="close-buttonn">&times;</span> </div>
            <div class="title-alert d-flex justify-content-center mt-2">Do You want to remove from favorites ?
            </div>
            <div class="d-flex justify-content-end mt-3">
                <button class="btn btn-outline-danger me-3 btn_remove_card">Remove</button>
                <button class="btn btn-outline-primary me-3 close-button">Cancel</button>
            </div>
        </div>
    </div>
    <script>
    var id_annonce;

    $("#active").prop("checked", true);

    $("#all").click(function() {
        msgs("all").then((data) => {
            $("#con").empty();
            $("#con").append(data);

            $(".li_right").click(function() {
                const id = $(this).attr("data-id");
                const text = $(this).find(".message").find(".sub_title")
                    .text();
                Swal.fire(text).then((result) => {
                    if (result.isConfirmed) {
                        $.post("app/boite_mail.php", {
                            type: 'seen',
                            id: id
                        }, (data) => {})
                    }
                })
            })
        }).catch((data) => {
            console.log(data);
        })
    })

    function msgs(type) {
        return new Promise(function(resolve, reject) {
            $.ajax({
                method: "POST",
                url: "app/boite_mail.php",
                data: {
                    type: type
                },
                success: function(data) {
                    resolve(data) // Resolve promise and go to then()
                },
                error: function(err) {
                    // console.log(err)
                    reject(err) // Reject the promise and go to catch()
                }
            });
        });
    }

    $(".li_right").click(function() {
        const id = $(this).attr("data-id");
        const text = $(this).find(".message").find(".sub_title")
            .text();
        Swal.fire(text).then((result) => {
            if (result.isConfirmed) {
                $.post("app/boite_mail.php", {
                    type: 'seen',
                    id: id
                }, (data) => {})
            }
        })
    })

    $("#unread").click(function() {
        msgs("unread").then((data) => {
            $("#con").empty();
            $("#con").append(data);

            $(".li_right").click(function() {
                const id = $(this).attr("data-id");
                const text = $(this).find(".message").find(".sub_title")
                    .text();
                Swal.fire(text).then((result) => {
                    if (result.isConfirmed) {
                        alert(id)
                        $.post("app/boite_mail.php", {
                            type: 'seen',
                            id: id
                        }, (data) => {})
                    }
                })
            })
        }).catch((data) => {
            console.log(data);
        })
    })

    $(".fa-chevron-down").click(function() {
        if ($(".select-menuu").hasClass("active")) {
            $(".select-menuu").removeClass("active");
        } else {
            $(".select-menuu").addClass("active");
            $(".3216").addClass("d-none")
        }
    });

    $(".fa-bell").click(function() {
        if ($(".3216").hasClass("d-none")) {
            $(".3216").removeClass("d-none");
            $(".select-menuu").removeClass("active")
        } else {
            $(".3216").addClass("d-none");
        }
    })
    //for the navbar
    // When the user scrolls the page, execute myFunction
    window.onscroll = function() {
        myFunction()
    };

    // Get the offset position of the navbar
    var sticky = $(".container").offset().top;

    // Add the sticky class to the navbar when you reach its scroll position. Remove "sticky" when you leave the scroll position
    function myFunction() {
        if ($(window).scrollTop() >= sticky) {
            $(".container").addClass("fixed")
        } else {
            $(".container").removeClass("fixed")
        }
    }

    $(".menuToggle").click(function() {
        $(".navigation").toggleClass("active");
    })

    //the modal traitment
    var elements = $('.modal-overlay, .modal');

    $('.annonce').click(function() {
        elements.addClass('active');
    });

    $('.annoncer').click(function() {
        elements.addClass('active');
    });

    $('.close-modal').click(function() {
        elements.removeClass('active');
    });

    $(".category").click(function() {
        let id = $(this).attr("data-cat-id");
        $(location).attr('href', 'annonce_traitment.php?id=' + id);
    })


    $("#active").click(function() {
        if ($('#active').is(':checked')) {
            $(".page-wrapper.pp45").empty();
            <?php  $data = $conn->query("SELECT a.id_announcer, a.id_user, i.titre_annonce, i.prix, i.etat, im.img, a.created_at FROM `infos_generales` i, `announcer` a, `images` im WHERE i.id_infos_Generales = a.id_infos_Generales and i.id_infos_Generales = im.id_infos_Generales $params AND status = 'active' GROUP BY a.id_announcer;"); ?>
            <?php  while($dt = $data->fetch(PDO::FETCH_ASSOC)){?>
            $(".page-wrapper.pp45").append(`<div class="page-inner miniCard" data-id-announce="<?php echo $dt['id_announcer']?>"
                                    data-id-user="<?php echo $dt['id_user']?>">
                                    <div class="el-wrapper">
                                        <div class="box-up">
                                            <img class="img" src="assets/images/<?php echo $dt['img']?>" alt="image">
                                            <div class="img-info">
                                                <div class="info-inner text-center">
                                                    <span class="p-name fw-bold m-auto p-1"
                                                        style="border-radius: 15px; background: #f5f5f5; width: fit-content;">
                                                        <?php echo $dt['titre_annonce']?>
                                                    </span>
                                                    <span class="p-company text-lowercase"><i
                                                            class="fa-solid fa-clock"></i>&nbsp;&nbsp;<?php echo 'il y a ' . datediff($conn, $dt['created_at'])?></span>
                                                </div>
                                                <div class="a-size">Etat : <span class="size">
                                                        <?php echo $dt['etat']?></span></div>
                                            </div>
                                        </div>

                                        <div class="box-down">

                                            <div class="h-bg">
                                                <div class="h-bg-inner"></div>
                                            </div>

                                            <a class="cart" href="#">
                                                <span class="price"><?php echo $dt['prix'] . ' DH'?></span>
                                                <span class="add-to-cart">
                                                    <span class="txt "
                                                        onclick="window.location.href='gallery.php?id=<?php echo $dt['id_announcer']?>'">Regarder</span>
                                                </span>
                                            </a>
                                        </div>
                                    </div>

                                </div>`)
            <?php }?>
        }
    })

    $("#moderation").click(function() {
        if ($('#moderation').is(':checked')) {
            $(".page-wrapper.pp45").empty();
            <?php  $data = $conn->query("SELECT a.id_announcer, a.id_user, i.titre_annonce, i.prix, i.etat, im.img, a.created_at FROM `infos_generales` i, `announcer` a, `images` im WHERE i.id_infos_Generales = a.id_infos_Generales and i.id_infos_Generales = im.id_infos_Generales $params AND status = 'moderation' GROUP BY a.id_announcer;"); ?>
            <?php  while($dt = $data->fetch(PDO::FETCH_ASSOC)){?>
            $(".page-wrapper.pp45").append(`<div class="page-inner miniCard" data-id-announce="<?php echo $dt['id_announcer']?>"data-id-user="<?php echo $dt['id_user']?>">
                                    <div class="el-wrapper">                                       
                                        <div class="box-up">                                           
                                            <img class="img" src="assets/images/<?php echo $dt['img']?>" alt="image">
                                            <div class="img-info">
                                                <div class="info-inner text-center">
                                                    <span class="p-name fw-bold m-auto p-1"
                                                        style="border-radius: 15px; background: #f5f5f5; width: fit-content;">
                                                        <?php echo $dt['titre_annonce']?>
                                                    </span>
                                                    <span class="p-company text-lowercase"><i
                                                            class="fa-solid fa-clock"></i>&nbsp;&nbsp;<?php echo 'il y a ' . datediff($conn, $dt['created_at'])?></span>
                                                </div>
                                                <div class="a-size">Etat : <span class="size">
                                                        <?php echo $dt['etat']?></span></div>
                                            </div>
                                        </div>

                                        <div class="box-down">
                                            <div class="h-bg">
                                                <div class="h-bg-inner"></div>
                                            </div>
                                            <a class="cart" href="#">
                                                <span class="price"><?php echo $dt['prix'] . ' DH'?></span>
                                                <span class="add-to-cart">
                                                    <span class="txt "
                                                        onclick="window.location.href='gallery.php?id=<?php echo $dt['id_announcer']?>'">Regarder</span>
                                                </span>
                                            </a>
                                        </div>
                                    </div>

                                </div>`)
            <?php }?>
        }
    })

    $("#rejete").click(function() {
        if ($('#rejete').is(':checked')) {
            $(".page-wrapper.pp45").empty();
            <?php  $data = $conn->query("SELECT a.id_announcer, a.id_user, i.titre_annonce, i.prix, i.etat, im.img, a.created_at FROM `infos_generales` i, `announcer` a, `images` im WHERE i.id_infos_Generales = a.id_infos_Generales and i.id_infos_Generales = im.id_infos_Generales $params AND status = 'rejected' GROUP BY a.id_announcer;"); ?>
            <?php  while($dt = $data->fetch(PDO::FETCH_ASSOC)){?>
            $(".page-wrapper.pp45").append(`<div class="page-inner miniCard" data-id-announce="<?php echo $dt['id_announcer']?>"data-id-user="<?php echo $dt['id_user']?>">
                                    <div class="el-wrapper">                                       
                                        <div class="box-up">                                           
                                            <img class="img" src="assets/images/<?php echo $dt['img']?>" alt="image">
                                            <div class="img-info">
                                                <div class="info-inner text-center">
                                                    <span class="p-name fw-bold m-auto p-1"
                                                        style="border-radius: 15px; background: #f5f5f5; width: fit-content;">
                                                        <?php echo $dt['titre_annonce']?>
                                                    </span>
                                                    <span class="p-company text-lowercase"><i
                                                            class="fa-solid fa-clock"></i>&nbsp;&nbsp;<?php echo 'il y a ' . datediff($conn, $dt['created_at'])?></span>
                                                </div>
                                                <div class="a-size">Etat : <span class="size">
                                                        <?php echo $dt['etat']?></span></div>
                                            </div>
                                        </div>

                                        <div class="box-down">
                                            <div class="h-bg">
                                                <div class="h-bg-inner"></div>
                                            </div>
                                            <a class="cart" href="#">
                                                <span class="price"><?php echo $dt['prix'] . ' DH'?></span>
                                                <span class="add-to-cart">
                                                    <span class="txt "
                                                        onclick="window.location.href='gallery.php?id=<?php echo $dt['id_announcer']?>'">Regarder</span>
                                                </span>
                                            </a>
                                        </div>
                                    </div>

                                </div>`)
            <?php }?>
        }
    })

    $("#supprimer").click(function() {
        if ($('#supprimer').is(':checked')) {
            $(".page-wrapper.pp45").empty();
            <?php  $data = $conn->query("SELECT a.id_announcer, a.id_user, i.titre_annonce, i.prix, i.etat, im.img, a.created_at FROM `infos_generales` i, `announcer` a, `images` im WHERE i.id_infos_Generales = a.id_infos_Generales and i.id_infos_Generales = im.id_infos_Generales $params AND status = 'supprimer' GROUP BY a.id_announcer;"); ?>
            <?php  while($dt = $data->fetch(PDO::FETCH_ASSOC)){?>
            $(".page-wrapper.pp45").append(`<div class="page-inner miniCard" data-id-announce="<?php echo $dt['id_announcer']?>"data-id-user="<?php echo $dt['id_user']?>">
                                    <div class="el-wrapper">                                       
                                        <div class="box-up">                                           
                                            <img class="img" src="assets/images/<?php echo $dt['img']?>" alt="image">
                                            <div class="img-info">
                                                <div class="info-inner text-center">
                                                    <span class="p-name fw-bold m-auto p-1"
                                                        style="border-radius: 15px; background: #f5f5f5; width: fit-content;">
                                                        <?php echo $dt['titre_annonce']?>
                                                    </span>
                                                    <span class="p-company text-lowercase"><i
                                                            class="fa-solid fa-clock"></i>&nbsp;&nbsp;<?php echo 'il y a ' . datediff($conn, $dt['created_at'])?></span>
                                                </div>
                                                <div class="a-size">Etat : <span class="size">
                                                        <?php echo $dt['etat']?></span></div>
                                            </div>
                                        </div>

                                        <div class="box-down">
                                            <div class="h-bg">
                                                <div class="h-bg-inner"></div>
                                            </div>
                                            <a class="cart" href="#">
                                                <span class="price"><?php echo $dt['prix'] . ' DH'?></span>
                                                <span class="add-to-cart">
                                                    <span class="txt "
                                                        onclick="window.location.href='gallery.php?id=<?php echo $dt['id_announcer']?>'">Regarder</span>
                                                </span>
                                            </a>
                                        </div>
                                    </div>

                                </div>`)
            <?php }?>
        }
    })

    function toggleModal() {
        $(".modall").toggleClass("show-modall");
    }

    $(".icon").click(function() {
        id_annonce = $(this).parent().parent().parent().attr("data-id-announce");
        toggleModal()
    })

    $(".close-buttonn").click(function() {
        toggleModal()
    });

    $(".close-button").click(function() {
        toggleModal()
    });

    $(window).click(function(event) {
        if (event.target == $(".modall")) {
            toggleModal()
        }
    });

    $(".btn_remove_card").click(function() {
        $.post("app/addtofavories.php", {
            id: id_annonce,
            type: 'remove'
        }, (data) => {
            if (data == true) {
                $("body").append(`<button class="trigger">Click here to trigger the modal!</button>
<div class="all456 alert alert-info d-flex justify-content-center">
  removed
</div>`)
                toggleModal();

                $(".all456").toggleClass("show")

                if ($(".all456").hasClass("show")) {
                    setTimeout(function() {
                        $(".all456").removeClass("show")
                    }, 1000)
                }

                location.reload();
            }
        })
    })

    $(".rty").click(function() {
        $(".rty").each(function() {
            $(this).removeClass("active");
        })
        $(this).addClass("active");

        if ($(this).hasClass("infos")) {
            $(".colinfo").removeClass("d-none");
            $(".colpass").addClass("d-none");
            console.log("we're in infos")
        } else {
            if ($(this).hasClass("pass")) {
                $(".colinfo").addClass("d-none");
                $(".colpass").removeClass("d-none");
                console.log("we're in pass")
            }
        }
    })


    $(".form-control").keypress(function() {
        $("#submit").prop("disabled", false);
    })

    $("select.form-control").on('change', function() {
        $("#submit").prop("disabled", false);
    })

    $(".ppps").keypress(function() {
        $("#sauvepass").prop("disabled", false);
    })

    $("#sauvepass").click(function() {
        var pass = true;
        $(".ppps").each(function() {
            if ($(this).val() == "") {
                pass = false;
            }
        })

        if (pass) {
            $.ajax({
                url: "app/updatemotpass.php", // Url of backend (can be python, php, etc..)
                type: "POST", // data type (can be get, post, put, delete)
                data: {
                    actuel: $('.actuel').val(),
                    new: $('.new').val(),
                    renew: $('.renew').val()

                }, // data in json format
                async: false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)
                success: function(response, textStatus, jqXHR) {
                    if (response == "votre mot de pass updated") {
                        Swal.fire({
                            icon: 'success',
                            text: response,
                        })
                        $('.actuel').val("")
                        $('.new').val("")
                        $('.renew').val("")
                    } else {
                        Swal.fire({
                            icon: 'error',
                            text: response,
                        })
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });
        } else {
            Swal.fire({
                icon: 'error',
                text: 'please fill the data',
            })
        }
    })

    $("#submit").click(function() {
        var pass = true;
        $(".pppi").each(function() {
            if ($(this).val() == "") {
                pass = false;
            }
        })

        if (pass) {
            $.ajax({
                url: "app/updateuserdata.php", // Url of backend (can be python, php, etc..)
                type: "POST", // data type (can be get, post, put, delete)
                data: {
                    name: $('.name').val(),
                    tel: $('.tel').val(),
                    city: $('#cities').val()

                }, // data in json format
                async: false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)
                success: function(response, textStatus, jqXHR) {
                    if (response == "updated successfully") {
                        Swal.fire({
                            icon: 'success',
                            text: response,
                        })

                    } else {
                        Swal.fire({
                            icon: 'error',
                            text: response,
                        })
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });
        } else {
            Swal.fire({
                icon: 'error',
                text: 'please fill the data',
            })
        }
    })
    </script>
</body>

</html>

</html>
