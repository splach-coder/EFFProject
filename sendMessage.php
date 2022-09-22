<?php 
    session_start();
    require_once 'app/db.php';
    require 'assets/php/functions.php';

    $buyerName = "";
    $buyeremail = "";
    if(isset($_SESSION['user_id'])  &&  isset($_SESSION['user_id_infos'])  &&  isset($_SESSION['user_email'])){
        $buyerId = $_SESSION['user_id'];
        $buyerName = getSingleValue($conn, "SELECT i.full_name FROM `users` u, `informations` i WHERE u.id_infos = i.id AND i.id = $buyerId");
        $buyeremail = $_SESSION['user_email'];
    }

    $sellerId = $_GET["id"];
    $annonceId = $_GET['infoId'];

    $sellerName = getSingleValue($conn, "SELECT i.full_name FROM `users` u, `informations` i WHERE u.id_infos = i.id AND i.id = $sellerId");
        $annonce = $conn->query("SELECT i.titre_annonce, i.description, i.prix, v.villename, CONCAT(DAY(a.created_at), ' ', MONTHNAME(a.created_at)) as created_at  FROM `announcer` a, `infos_generales` i, `ville` v WHERE a.id_infos_Generales = i.id_infos_Generales AND i.id_Ville = v.id_Ville AND i.id_infos_Generales = $annonceId")->fetch();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--Links-->
    <?php include_once 'envirmentLinks.php'?>

    <!--Private Links-->
    <link rel="stylesheet" href="assets/css/firstPageStyle.css">
    <link rel="stylesheet" href="assets/css/SendMessage.css">
    <link rel="stylesheet" href="assets/css/modal.css">
    <link href="https://fonts.googleapis.com/css?family=Medula+One" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,500;1,400&display=swap" rel="stylesheet">
</head>

<body>
    <!--Nav Bar-->
    <?php include_once 'normalNavbar.php'?>

    <div class="container r my-5">
        <div class="row">
            <div class="col">

                <h2 class="superTitle">Envoyer message à « <?php echo $sellerName ?>»</h2>
                <div class="row mt-1">
                    <label for="exampleFormControlInput3" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="exampleFormControlInput3" name="S_fullname"
                        placeholder="John Doe" value="<?php echo $buyerName?>">
                </div>
                <div class="row mt-2">
                    <label for="exampleFormControlInput1" class="form-label">E-mail</label>
                    <input type="email" class="form-control" id="exampleFormControlInput1"
                        placeholder="name@example.com" name="S_email" value="<?php echo $buyeremail?>">
                </div>
                <div class="row mt-2">
                    <label for="textarea">Description</label>
                    <textarea name="" id="desc" cols="30" rows="4" placeholder="Ecriver Votre Message ici"></textarea>
                </div>

                <button class="btn btn-primary mt-3 btnEnvoyer">Envoyer Votre Message</button>

                <div class="row mt-2">
                    <h5 class="atten">Attention :</h5>
                    <p class="msge">
                        Il ne faut jamais envoyer de l’argent par virement bancaire ou à travers les agences de
                        transfert
                        d’argent lors de l’achat des biens disponibles sur le site. crowd Shoppe n’est pas garant
                        des
                        transactions et ne joue pas le rôle d’intermédiaire.</p>
                </div>
            </div>
            <div class="col ms-2">
                <div class="row">
                    <h4>Résumé de l'annonce</h4>
                    <p class="rp">&nbsp <?php echo $annonce['titre_annonce']?></p>
                    <p class="rp">&nbsp <?php echo $annonce['prix']?></p>
                    <p class="rp">&nbsp <?php echo $annonce['created_at']?></p>
                </div>
                <div class="line">

                </div>
                <div class="row">
                    <h4>Description</h4>
                    <p class="rp">&nbsp <?php echo $annonce['description']?></p>
                </div>
                <div class="line">

                </div>
                <div class="row">
                    <h4>Localisation</h4>
                    <p class="rp">&nbsp <?php echo $annonce['villename']?></p>
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
                    <div class="row" data-cat-id="<?php echo $dt['id_Cat']?>">
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


    <script>
    $(".menuToggle").click(function() {
        $(".navigation").toggleClass("active");
    })
    <?php
    if (isset($_SESSION['user_id']) && isset($_SESSION['user_id_infos']) && isset($_SESSION[
            'user_email'])) {
        ?>
    $(".form-control").prop('disabled', true);
    $(".btnEnvoyer").removeClass("disabled")
    <?php }else{ ?>
    $(".btnEnvoyer").addClass("disabled")
    <?php }?>


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

    //the modal traitment
    var elements = $('.modal-overlay, .modal');

    $('.annonce').click(function() {
        elements.addClass('active');
    });

    $('.annoncer').click(function() {
        elements.addClass('active');
    });

    $(".category").click(function() {
        let id = $(this).attr("data-cat-id");
        $(location).attr('href', 'annonce_traitment.php?id=' + id);
    })

    $('.close-modal').click(function() {
        elements.removeClass('active');
    });

    $(".btnEnvoyer").click(function() {
        showUser($("#desc").val());
        $(location).attr('href', 'http://localhost/project/chat-part.php?chat=' + <?php echo $sellerId?> +
            '&announce=' + <?php echo $annonceId?>);
    })

    function showUser(str) {
        /*$.ajax({
        url: "app/msg.php", // Url of backend (can be python, php, etc..)
        type: "POST", // data type (can be get, post, put, delete)
        data: {
            msg: str,
            user: <?php echo $buyerId?>,
            chat: <?php echo $sellerId?>
        }, // data in json format
        async: false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)
        success: function(response, textStatus, jqXHR) {
            $(".chatBx").empty();
            $(".chatBx").append(response);
            $('#chat-place').val('');
            $(".chatBx").animate({
                scrollTop: $(".chatBx").height()
            }, 100);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });*/
        $.ajax({
            url: "app/msg.php", // Url of backend (can be python, php, etc..)
            type: "POST", // data type (can be get, post, put, delete)
            data: {
                msg: str,
                chat: <?php echo $sellerId?>,
                announce: <?php echo $annonceId?>
            }, // data in json format
            async: false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)
            success: function(response, textStatus, jqXHR) {
                $(".chatBx").empty();
                $(".chatBx").append(response);
                $('#chat-place').val('');
                $(".chatBx").animate({
                    scrollTop: $(".chatBx").height()
                }, 100);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    }
    </script>
</body>

</html>
