<?php 
    session_start();

    //var_dump($_SESSION);die;
    require_once 'app/db.php';
    include 'assets/php/functions.php';
    $number = getSingleValue($conn, "SELECT COUNT(*) FROM `announcer` where status = 'active'")
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="website, blog, foo, bar">
    <meta name="author" content="John Doe">
    <meta name="publisher" content="John Doe">
    <meta name="copyright" content="John Doe">
    <meta name="description" content="This short description describes my website.">
    <meta name="page-topic" content="Media">
    <meta name="page-type" content="Market Place">
    <meta name="audience" content="Everyone">
    <meta name="robots" content="index, follow">

    <!--Links-->
    <?php include_once 'envirmentLinks.php'?>

    <!--Private Links-->
    <link rel="stylesheet" href="assets/css/firstPageStyle.css">
    <link rel="stylesheet" href="assets/css/cards.css">
    <link rel="stylesheet" href="assets/css/modal.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <link href="https://fonts.googleapis.com/css?family=Medula+One" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,500;1,400&display=swap" rel="stylesheet">


    <script src="assets/javascript/functions.js">

    </script>

    <style>
    .lko136 {
        border-radius: 20px;
        padding: 10px 15px;
        margin: 2px 5px;
        cursor: pointer;
    }

    .lko136:hover {
        background-color: #ddd;
        border: 1px solid #555 !important;
    }

    .el-wrapper .box-up label {
        position: absolute;
        right: 0;
        z-index: 1010;
    }

    .el-wrapper .box-up label input[type="checkbox"] {
        opacity: 0;
        position: absolute;
        cursor: pointer;
    }

    .el-wrapper .box-up label .icon {
        position: relative;
        width: 30px;
        height: 30px;
        color: #555;
        font-size: 1.4rem;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        margin: 0 10px;
        border-radius: 10px;
        overflow: hidden;
    }

    .cards {
        display: flex;
        justify-content: center;
        align-items: flex-start;
    }

    .cf98 {
        background-color: #F8F9FA;
        padding: 1rem 1.5rem;
        padding-bottom: 1.5rem;
        border-radius: 15px;
        border-top: 5px solid #6C63FF;
    }

    .searchbtn {
        position: relative;
        top: -15px;
    }

    .circle {
        background-color: #4A4A4A;
        color: #FFFFFF;
        font-family: 'Rubik', sans-serif;
        font-size: 24px;
        border-radius: 50%;
        width: 100px;
        height: 100px;
        display: flex;
        justify-content: center;
        align-items: center;
        line-height: 79px;
    }

    </style>

</head>

<body>

    <nav class="sub-nav d-flex justify-content-end">
        <i style="color: #f5f5f5; cursor:pointer;" class="fa-solid fa-headphones me-2"></i><span class="me-5"
            style="color: #f5f5f5;font-family: 'Rubik', sans-serif;font-size:12px; line-height: 18px;cursor:pointer;">
            Aide et renseignements</span>
    </nav>
    <!--Nav Bar-->
    <?php include_once 'normalNavbar.php'?>
    <div class="container mt-3">
        <div style="width: 100%;" class="d-flex justify-content-center">
            <p class="d-flex flex-column align-items-center my-3"
                style="font-family: 'Rubik', sans-serif;font-size:32px; line-height: 38px; color: #2D2D2D;">
                1er site d'annonces gratuites au Maroc <br />
                <span class="mt-2"
                    style="font-family: 'Rubik', sans-serif;font-size:16px; line-height: 24px; color: #666666;">
                    Trouver la bonne affaire parmi <?=$number?> annonces
                </span>
            </p>
        </div>
        <div class="row">
            <h3 class="mt-3">Découvrez nos catégories</h3>
            <!--First slider-->
            <div class="container-fluid cf98 shadow">
                <div class="cards">
                    <?php  $data = $conn->query("SELECT * FROM `categories`"); ?>
                    <?php  while($dt = $data->fetch(PDO::FETCH_ASSOC)){?>
                    <div class="card" data-cat-id="<?php echo $dt['id_Cat']?>">
                        <div class="row image">
                            <img src="assets/icons/<?php echo $dt['icon']?>" alt="icon">
                        </div>
                        <div class="row title">
                            <p><?php echo $dt['Name_Cat']?></p>
                        </div>
                    </div>
                    <?php }?>
                </div>
            </div>
            <div class="row d-flex flex-centerY-centerX">
                <button class="btn searchbtn" style="width: auto; height: 40px;
                "><i class="fas fa-search">&nbsp&nbsp
                        RECHERCHER&nbsp(<span id="number"><?php echo $number;?></span>)
                    </i></button>
            </div>
            <!--end Slider-->
        </div>
        <h5 class="mt-4">Ces annonces peuvent vous intéresser</h5>
    </div>
    <!-- users products -->
    <div class="container-fluid text-center mb-4 cardscon h-25" style="overflow: hidden;">
        <div class="row d-flex">
            <div class="demo">
                <div class="mainCard">
                    <div class="mainCardContent page-wrapper">
                        <?php  $data = $conn->query("SELECT a.id_announcer, a.id_user, i.titre_annonce, i.prix, i.etat, im.img, a.created_at, count(*) 
FROM `infos_generales` i, `announcer` a, `images` im, `announce_views` av 
WHERE a.id_announcer = av.id_announce 
AND  a.id_infos_Generales = i.id_infos_Generales 
AND im.id_infos_Generales = i.id_infos_Generales
AND a.status = 'active'
GROUP BY a.id_announcer
ORDER BY count(*) DESC LIMIT 5;"); ?>
                        <?php  while($dt = $data->fetch(PDO::FETCH_ASSOC)){?>
                        <div class="page-inner miniCard shadow" data-id-announce="<?php echo $dt['id_announcer']?>"
                            data-id-user="<?php echo $dt['id_user']?>">
                            <div class="el-wrapper">
                                <div class="box-up">
                                    <label>
                                        <input type="checkbox" class="c">
                                        <div class="icon">
                                            <i class="fas fa-heart" data-bs-toggle="tooltip"
                                                title="Add to favories!"></i>
                                        </div>
                                    </label>
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
                                                onclick="window.location.href='gallery.php?id=<?php echo $dt['id_announcer']?>'">
                                                VOIR
                                            </span>
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
        <a href="products.php?id=-1&city=&search=" class="btn btn-primary p-2"> <i class="fas fa-plus"></i> Voir
            Plus</a>
    </div>
    <!-- menu -->
    <div class="container mb-4">
        <div class="accordion" id="accordionExample">
            <div class="accordion-item shadow">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button " type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Ville populaires
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                    data-bs-parent="#accordionExample">
                    <div class="accordion-body d-flex flex-wrap">
                        <?php $data = $conn->query("SELECT v.id_Ville, v.villename, COUNT(*) AS 'cpt' FROM `ville` v, `infos_generales` i WHERE v.id_Ville = i.id_Ville GROUP BY v.villename ORDER BY `cpt` DESC LIMIT 10;");
                            while($dt = $data->fetch(PDO::FETCH_ASSOC)){
                        ?>
                        <div class="border lko136 v" data-id="<?php echo $dt['id_Ville']?>">
                            <?php echo $dt['villename']?>
                        </div>
                        <?php }?>
                    </div>
                </div>
            </div>
            <div class="accordion-item shadow">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Recherches populaires
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                    data-bs-parent="#accordionExample">
                    <div class="accordion-body d-flex flex-wrap">
                        <?php $data = $conn->query("SELECT search , count(*) as 'cpt' FROM `searchs`  GROUP BY search ORDER BY cpt DESC  LIMIT 10;");
                            while($dt = $data->fetch(PDO::FETCH_ASSOC)){
                        ?>
                        <div class="border lko136 s"><?=$dt['search']?></div>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>

        <div class="eventBox d-flex justify-content-center mt-5 "
            style="position: relative; width: 100%;border-radius: 30px;">
            <img src=" assets/icons/ACHETEZ ET VENDEZ PARTOUT!.png" style="border-radius: 30px;" alt="" width="80%"
                height="300px" class="shadow">
        </div>

        <div class="m-auto d-flex justify-content-around" style="width: 80%">
            <div class="d-flex p-4 justify-content-center align-items-center my-5"
                style="border-radius: 15px;background-color:#F5F5F5;opacity: 0.9;">
                <div class="circle me-4" style="opacity: 0.9;"><span>73%</span></div>
                <div class="text d-flex justify-content-center align-items-center"
                    style="font-size: 20px;line-height: 24px;color: #4a4a4a; font-family: 'Rubik', sans-serif;">Des
                    objets et
                    annonces
                    sont
                    <br />
                    vendus
                    en moins de 3 jours
                </div>
            </div>
            <div class="d-flex p-4 justify-content-center align-items-center my-5"
                style="border-radius: 15px;background-color:#F5F5F5;opacity: 0.9;">
                <div class="circle me-4" style="opacity: 0.9;"><span>92%</span></div>
                <div class="text d-flex justify-content-center align-items-center"
                    style="font-size: 20px;line-height: 24px;color: #4a4a4a; font-family: 'Rubik', sans-serif;">
                    De nos utilisateurs sont <br> satisfaits
                </div>
            </div>
        </div>
    </div>
    <!-- end menu -->
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

    <script>
    $(document).ready(function() {

        $(function() {
            <?php check($conn);?>

            var city;
            $.getJSON("https://api.ipify.org?format=jsonp&callback=?",
                function(json) {
                    const ip = json.ip;
                    var api_key = "at_hwkrGNeDdxy1B4sqYK5sgJe3U4NsK";
                    $(function() {
                        $.ajax({
                            url: "https://geo.ipify.org/api/v1",
                            data: {
                                apiKey: api_key,
                                ipAddress: ip
                            },
                            success: function(data) {
                                city = data.location.city;
                            }
                        });
                    });

                    setTimeout(function() {
                        $.ajax({
                            method: "POST",
                            url: "app/visitors.php",
                            data: {
                                ip: ip,
                                city: city
                            },
                            success: function(
                                data
                            ) { // Resolve promise and go to then()
                            },
                            error: function(err) {
                                // console.log(err)
                                reject(
                                    err
                                ) // Reject the promise and go to catch()
                            }
                        });
                    }, 5000)


                }
            );
        });







        $("#all").click(function() {
            msgs("all").then((data) => {
                $("#con").empty();
                $("#con").append(data);

                $(".li_right").click(function() {
                    const id = $(this).attr("data-id");
                    const text = $(this).find(".message").find(".sub_title")
                        .text();
                    const link = $(this).find(".message").attr("data-link");

                    var lnk = '<a href="' + link +
                        '">see my announces?</a>';

                    if (link == null) {
                        Swal.fire({
                            text: text,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.post("app/boite_mail.php", {
                                    type: 'seen',
                                    id: id
                                }, (dt) => {

                                })
                            }
                        })
                    } else {
                        Swal.fire({
                            text: text,
                            footer: lnk
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.post("app/boite_mail.php", {
                                    type: 'seen',
                                    id: id
                                }, (dt) => {

                                })
                            }
                        })
                    }

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
            const link = $(this).find(".message").attr("data-link");
            Swal.fire({
                text: text,
                footer: '<a href="' + link + '">see my announces?</a>'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post("app/boite_mail.php", {
                        type: 'seen',
                        id: id
                    }, (data) => {
                        $("#all").click();
                    })
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
                    const link = $(this).find(".message").attr("data-link");
                    Swal.fire({
                        text: text,
                        footer: '<a href="' + link +
                            '">see my announces?</a>'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.post("app/boite_mail.php", {
                                type: 'seen',
                                id: id
                            }, (dt) => {
                                $("#con").empty();
                                $("#con").append(data);
                            })
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
                $(".3216").addClass("d-none");
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
        var sticky = $(".main-nav").offset().top;

        // Add the sticky class to the navbar when you reach its scroll position. Remove "sticky" when you leave the scroll position


        function myFunction() {
            if ($(window).scrollTop() >= sticky) {
                $(".main-nav").addClass("sticky")
            } else {
                $(".main-nav").removeClass("sticky")
            }
        }


        //check
        $(window).resize(() => {
            if ($(window).width() > 991) {
                $(".cardscon").removeClass("container-fluid");
                $(".cardscon").addClass("container");
                $(".mainCardContent").addClass("page-wrapper");
            } else {
                $(".cardscon").removeClass("container");
                $(".cardscon").addClass("container-fluid");
                $(".mainCardContent").removeClass("page-wrapper");
            }
        })


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

        let catid = -1;
        $(".card").click(function() {
            const id = $(this).attr("data-cat-id");
            catid = $(this).attr("data-cat-id");
            $.post("app/nbranounces.php", {
                id: id
            }, (data) => {
                if (data) {
                    $("#number").text(data);
                }
            })
        })

        $(".searchbtn").click(function() {
            window.location.href = 'products.php?id=' + catid + '&city=' +
                '&search=';
        })

        $(".lko136.v").click(function() {
            const city = $(this).attr("data-id");
            window.location.href = 'products.php?id=' + catid + '&city=' + city +
                '&search=';
        })
        $(".lko136.s").click(function() {
            const search = $(this).text();
            window.location.href = 'products.php?id=' + catid + '&city=' +
                '&search=' + search;
        })

        $(".c").change(function() {
            const id = $(this).parent().parent().parent().parent().attr(
                "data-id-announce");
            if ($(this).is(":checked")) {
                $.post("app/addtofavories.php", {
                    id: id,
                    type: 'add'
                }, (data) => {
                    if (data == true) {
                        $(".el-wrapper .box-up label input[type='checkbox']:checked~.icon .fas")
                            .css({
                                color: '#FF4949',
                                textShadow: '0 0 5px #FF4949'
                            })
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'article has been saved in favories',
                            showConfirmButton: false,
                            timer: 1100
                        })
                    } else {
                        Swal.fire({
                            position: 'center',
                            icon: 'info',
                            html: `<form class="px-5 form-control border-0 pt-4" method="post" action="app/loginBack-end.php">
        <div class="row my-3">
            <label for="">E-mail</label><br>
            <input type="email" name="Lemail" placeholder="E-mail">
        </div>
        <div class="row my-3">
            <label for="">Mot de passe</label><br>
            <input type="password" name="Lpass" placeholder="Mot de passe">
        </div>
        <div class="row  my-3">
            <a href="" class="ms-auto">Mot de passe oublie ?</a>
        </div>
        <div class="row  my-3">
            <button type="submit" class="btn btn-primary">SE CONNECTER</button>
        </div>
    </form>`,
                            showConfirmButton: false,
                        })
                    }
                })
            } else {
                $.post("app/addtofavories.php", {
                    id: id,
                    type: 'remove'
                }, (data) => {
                    if (data == true) {
                        $(".toast").removeClass("show");
                        $(this).next().children()
                            .css({
                                color: '#555',
                                textShadow: 'none'
                            })

                        Swal.fire({
                            position: 'center',
                            icon: 'info',
                            title: 'article has been removed in favories',
                            showConfirmButton: false,
                            timer: 1100
                        })
                    }
                })
            }

        })


        //make the the favories works
        <?php if(isset($_SESSION['user_id'])  &&  isset($_SESSION['user_id_infos'])  &&  isset($_SESSION['user_email'])){
            $idUser = $_SESSION['user_id'];
            $sqlj = "SELECT id_annonce FROM `favories` WHERE id_user = $idUser";    
        ?>

        $(".c").each(function() {
            const id = $(this).parent().parent().parent().parent().attr(
                "data-id-announce");
            <?php  $datas = $conn->query($sqlj); ?>
            <?php  while($dt = $datas->fetch(PDO::FETCH_ASSOC)){?>
            if (id == <?php echo $dt['id_annonce']?>) {
                $(this).next().children()
                    .css({
                        color: '#FF4949',
                        textShadow: '0 0 5px #FF4949'
                    })
            }
            <?php }?>
        });

        <?php }?>
    })
    </script>
</body>

</html>

</html>
