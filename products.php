<?php
session_start();
require_once 'app/db.php';
require 'assets/php/functions.php';

$number = getSingleValue($conn, "SELECT COUNT(*) FROM `announcer` where status = 'active'");
$min = getSingleValue($conn, "SELECT min(prix) FROM `infos_generales`;");
$max = getSingleValue($conn, "SELECT max(prix) FROM `infos_generales`;");
$catid = $_GET['id'];
$city = $_GET['city'];
$search = $_GET['search'];

if(isset($_GET['max'])){
    $Vmax = $_GET['max'];
}else{
    $Vmax = $max;
}
$param = "";

if($catid != -1){
    if(empty($city)){
        if(empty($search)){
            $param = " AND i.id_Cat = $catid "; //just the id
        }else{
            $param = " AND i.id_Cat = $catid AND LOCATE('$search', `titre_annonce`) > 0 "; //id and search
        }
        
    }else{
        if(empty($search)){
            $param = " AND i.id_Cat = $catid AND i.id_Ville = $city "; //id and city
        }else{
            $param = " AND i.id_Cat = $catid AND i.id_Ville = $city AND LOCATE('$search', `titre_annonce`) > 0 "; //id and city and search
        }
    }
}else{
    if(!empty($city)){    
        if(!empty($search)){
            $param = " AND i.id_Ville = $city AND LOCATE('$search', `titre_annonce`) > 0 "; //city and search
        }else{
            $param = " AND i.id_Ville = $city "; //just the city
        }
    }else{
        $param = " AND LOCATE('$search', `titre_annonce`) > 0 "; //just the search
    }
}




$query = "SELECT a.id_announcer, a.id_user, i.titre_annonce, i.prix, i.etat, im.img, a.created_at FROM `infos_generales` i, `announcer` a, `images` im WHERE i.id_infos_Generales = a.id_infos_Generales and i.id_infos_Generales = im.id_infos_Generales $param AND a.status = 'active' AND i.prix <= $Vmax GROUP BY a.id_announcer;";
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
    <link rel="stylesheet" href="assets/css/cards.css">
    <link rel="stylesheet" href="assets/css/modal.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <link href="https://fonts.googleapis.com/css?family=Medula+One" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,500;1,400&display=swap" rel="stylesheet">


    <style>
    input,
    select {
        display: block;
        width: 100%;
        padding: 10px;
        border: none;
        background-color: #F8F9FA;
    }

    input:focus {
        border: none;
        outline: none;
    }

    .input-icon-wrap {
        display: flex;
        flex-direction: row;
        border: 1px solid #ccc;
        border-radius: 0.25rem;
        position: relative;
    }

    .kjhui {
        background-color: #F8F9FA;
        padding-bottom: 1rem;
        border-radius: 0.25rem;
    }

    .searchbtn {
        position: relative;
        top: -15px;
    }

    .input-icon,
    .input-with-icon {
        padding: 10px;
    }

    .page-wrapper {
        height: 100%;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        overflow: hidden;
        overflow-y: scroll;
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

    .toast {
        display: none !important;
        position: absolute;
        left: -90%;
        transition: all .5s ease-in-out;
        border-radius: 15px;
        background: #29A160;
    }

    .toast .text,
    .btn-close {
        color: #f5f5f5;
        font-family: 'Rubik', sans-serif;
        font-size: 16px;
        font-weight: 400;
        Line-height: 24px;
    }

    .toast.show {
        display: block;
        left: 5%;
    }


    /*price range styling */

    .range-slider {
        width: 100%;
    }

    .range-slider__range {
        -webkit-appearance: none;
        width: calc(100% - (73px));
        height: 10px;
        border-radius: 5px;
        background: #d7dcdf;
        outline: none;
        padding: 0;
        margin: 0;
    }

    .range-slider__range::-webkit-slider-thumb {
        appearance: none;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #2c3e50;
        cursor: pointer;
        transition: background 0.15s ease-in-out;
    }

    .range-slider__range::-webkit-slider-thumb:hover {
        background: #1abc9c;
    }

    .range-slider__range:active::-webkit-slider-thumb {
        background: #1abc9c;
    }

    .range-slider__range::-moz-range-thumb {
        width: 15px;
        height: 15px;
        border: 0;
        border-radius: 50%;
        background: #2c3e50;
        cursor: pointer;
        transition: background 0.15s ease-in-out;
    }

    .range-slider__range::-moz-range-thumb:hover {
        background: #1abc9c;
    }

    .range-slider__range:active::-moz-range-thumb {
        background: #1abc9c;
    }

    .range-slider__range:focus::-webkit-slider-thumb {
        box-shadow: 0 0 0 3px #fff, 0 0 0 6px #1abc9c;
    }

    .range-slider__value {
        position: relative;
        min-width: 80px;
        color: #fff;
        line-height: 18px;
        text-align: center;
        border-radius: 3px;
        background: #2c3e50;
        padding: 5px 10px;
        margin-left: 15px;
    }

    .range-slider__value:after {
        position: absolute;
        top: 8px;
        left: -7px;
        width: 0;
        height: 0;
        border-top: 7px solid transparent;
        border-right: 7px solid #2c3e50;
        border-bottom: 7px solid transparent;
        content: "";
    }

    ::-moz-range-track {
        background: #d7dcdf;
        border: 0;
    }

    input::-moz-focus-inner,
    input::-moz-focus-outer {
        border: 0;
    }

    </style>
</head>

<body>
    <!--Nav Bar-->
    <?php include_once 'normalNavbar.php'?>


    <div class="container my-5">
        <div class="row">
            <h3 class="mt-3">Découvrez nos catégories</h3>
            <div class="container-fluid flex-column d-flex justify-content-center align-content-center kjhui shadow">
                <div class="row mt-3 m-auto d-flex justify-content-around" style="width: 100%;">
                    <div class="col input-icon-wrap me-3">
                        <span class="input-icon">
                            <span class="fa fa-search"></span>
                        </span>
                        <input type="text" class="input-with-icon" style="border: none !important;" id="searchinput"
                            name="search" placeholder="Que recherchez vous?">
                    </div>
                    <div class="col  input-icon-wrap me-3">
                        <span class="input-icon">
                            <span class="fa fa-location-dot"></span>
                        </span>
                        <select name="villes" id="vils">
                            <option selected>Selectioner une ville</option>
                            <?php  $data = $conn->query("SELECT * FROM `ville`"); ?>
                            <?php  while($dt = $data->fetch(PDO::FETCH_ASSOC)){?>
                            <option value="<?php echo $dt['id_Ville']?>">
                                <?php echo $dt['villename']?>
                            </option>
                            <?php }?>
                        </select>
                    </div>
                    <div class="col  input-icon-wrap d-flex align-items-center">
                        <div class="range-slider d-flex align-items-center">
                            <input class="range-slider__range" id="range" type="range" value="<?=$Vmax?>"
                                min="<?=$min?>" max="<?=$max?>">
                            <span class="range-slider__value">0</span>
                        </div>
                    </div>
                </div>
                <!--First slider-->
                <!-- Swiper -->
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
            <div class="row d-flex justify-content-center">
                <button class="btn btn-primary searchbtn" style="width: auto; height: 40px;
                font-family:Rubik, Roboto, Tajawal, -apple-system, BlinkMacSystemFont,
                 sans-serif; line-height:
                    14px; letter-spacing: 2px;"><i class="fas fa-search">&nbsp&nbsp
                        RECHERCHER&nbsp(<span id="number"><?php echo $number;?></span>)
                    </i></button>
            </div>
            <!--end Slider-->
        </div>

        <div class="row mt-5">
            <div class="col overflow-hidden d-flex flex-wrap">
                <div class="demo">
                    <div class="mainCard">
                        <div class="mainCardContent page-wrapper overflow-hidden">
                            <?php  $data = $conn->query($query); ?>
                            <?php  while($dt = $data->fetch(PDO::FETCH_ASSOC)){?>
                            <div class="page-inner miniCard shadow rounded-2"
                                data-id-announce="<?php echo $dt['id_announcer']?>"
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
            <!--div class="col-4">
                anas
            </div-->
        </div>
    </div>

    <!--footer-->
    <?php include 'footer.php'?>
    <!--end footer-->

    <div class="toast px-2 py-3 d-flex justify-content-between align-items-center">
        <div class="text d-flex align-items-center">
            <i class="fas fa-circle-check ms-2 fs-5"></i>
            <span class="ms-4">Some text inside the toast body</span>
        </div>
        <div class="d-flex ">
            <button type="button" class="btn-close"></button>
        </div>
    </div>

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

        $("#vils option[value='<?=$city?>']").attr('selected', 'true');

        //make the the favories works
        <?php if(isset($_SESSION['user_id'])  &&  isset($_SESSION['user_id_infos'])  &&  isset($_SESSION['user_email'])){
            $sqlj = "SELECT id_annonce FROM `favories` where id_user =" . $_SESSION['user_id'];    
        ?>

        $(".c").each(function() {
            const id = $(this).parent().parent().parent().parent().attr(
                "data-id-announce");
            <?php  $datas = $conn->query($sqlj); ?>
            <?php  while($dt = $datas->fetch(PDO::FETCH_ASSOC)){?>
            if (id == <?=$dt['id_annonce']?>) {
                $(this).next().children()
                    .css({
                        color: '#FF4949',
                        textShadow: '0 0 5px #FF4949'
                    })
            }
            <?php }?>
        });

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

        $(".c").change(function() {
            const id = $(this).parent().parent().parent().parent().attr(
                "data-id-announce");
            if ($(this).is(":checked")) {
                $.post("app/addtofavories.php", {
                    id: id,
                    type: 'add'
                }, (data) => {
                    if (data == true) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'article has been saved in favories',
                            showConfirmButton: false,
                            timer: 1100
                        })
                        $(".el-wrapper .box-up label input[type='checkbox']:checked~.icon .fas")
                            .css({
                                color: '#FF4949',
                                textShadow: '0 0 5px #FF4949'
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

        $(".btn-close").click(function() {
            $(".toast").removeClass("show");
        });



    })

    $(".menuToggle").click(function() {
        $(".navigation").toggleClass("active");
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

    let city = "";


    $(".searchbtn").click(function() {
        let serach = $("#searchinput").val();
        if (serach != "") {
            console.log("working");
            $.ajax({
                url: "app/addsearch.php", // Url of backend (can be python, php, etc..)
                type: "POST", // data type (can be get, post, put, delete)
                data: {
                    msg: serach

                }, // data in json format
                async: false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)
                success: function(response, textStatus, jqXHR) {
                    console.log(response);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });
        }

        window.location.href = 'products.php?id=' + catid + '&city=' + city + '&search=' + serach +
            "&max=" + $("#range").val();
    })


    $("#vils").change(function() {
        city = $(this).val();
    });

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


    ///price range code
    var rangeSlider = function() {
        var slider = $('.range-slider'),
            range = $('.range-slider__range'),
            value = $('.range-slider__value');



        slider.each(function() {

            value.each(function() {
                var value = $(this).prev().attr('value');
                $(this).html("<span>" + value + " DH</span>");
            });

            range.on('input', function() {
                $(this).next(value).html("<span>" + this.value + " DH</span>");
            });
        });
    };

    rangeSlider();
    </script>
</body>

</html>
