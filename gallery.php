<?php 
    session_start();
    require_once 'app/db.php';
    include 'assets/php/functions.php';
    
    $Id = $_GET['id'];
    $buyerId = null;

    $query = "SELECT a.id_announcer, i.id_infos_Generales, u.id, i.titre_annonce, v.villename, i.etat, i.prix, i.description, u.picture, inf.full_name, inf.telephone, CONCAT(DAY(a.created_at), ' ', MONTHNAME(a.created_at)) as created_at , status
    FROM `infos_generales` i, `informations` inf, `announcer` a, `users` u, `ville` v 
    WHERE i.id_infos_Generales = a.id_infos_Generales
    AND a.id_user = u.id 
    AND i.id_Ville = v.id_Ville 
    AND inf.id = u.id_infos 
    AND a.id_announcer = $Id";

    $stmt = $conn->query($query);
    $annonce = $stmt->fetch();

    $idinfos = $annonce['id_infos_Generales'];
    $idannounce = $annonce['id_announcer'];
    $status = $annonce['status'];
    $userID = $annonce['id'];
    $num = $annonce["telephone"];
    $imagequery = "SELECT img FROM `images` WHERE id_infos_Generales = $idinfos";
    $stm2 = $conn->query($imagequery);


   
    if(isset($_SESSION['user_id'])){
        $buyerId = $_SESSION['user_id'];

        if ($buyerId != $userID){
            $stmt = $conn->prepare("SELECT EXISTS (SELECT * FROM announce_views WHERE id_user = $buyerId AND id_announce =  $idannounce) as view");
                                
            //execute the statement
            $stmt->execute();
            
            //fetch result
            $view = $stmt->fetch();
            $check = $view['view'];
            
            if($check == 0){
                $sql1 = "INSERT INTO `announce_views`(`views`, `id_user`, `id_announce`) VALUES (1, $buyerId, $idannounce)";
                $conn->exec($sql1);
            }
        }
    }  
    else{
        $sql1 = "INSERT INTO `announce_views`(views, id_announce) VALUES (1, $idannounce)";
        $conn->exec($sql1);
    }
    
    
    $views = getSingleValue($conn, "SELECT COUNT(*) FROM `announce_views` WHERE `id_announce` = " . $idannounce);
    $mss = getSingleValue($conn, "SELECT count(*) FROM `chat_table` WHERE id_announce = $idannounce AND to_user = $userID");
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
    <link rel="stylesheet" href="assets/css/modal.css">
    <link rel="stylesheet" href="assets/css/gaalllery.css">
    <link href="https://fonts.googleapis.com/css?family=Medula+One" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,500;1,400&display=swap" rel="stylesheet">
    <script src="assets/javascript/functions.js"></script>
    <!--Resources-->
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

    <script
        src="https://www.paypal.com/sdk/js?client-id=AUIuBkHwueUPmw1n8kat1Mn1hiUsn_V5skv9x27Kwck7oVRzCkgacqzWGRRHd9rBiiNeQjlGyhULSN-0&currency=USD">
    </script>
</head>

<body>
    <?php include_once 'normalNavbar.php'?>
    <div class="container mt-5 p-2 rounded shadow">
        <nav class="main-nav navbar navbar-expand-lg navbar-light bg-light rounded-2">
            <div class=" container-fluid">
                <a class="navbar-brand" href="#"><img src="assets/images/<?php echo $annonce['picture']?>" alt=""
                        width="30" height="24" class=" align-text-top">
                </a></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll1"
                    aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="nvlinks collapse navbar-collapse" id="navbarScroll1">
                    <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                        <li class="nav-item">
                            <p class="user-name"><?php echo $annonce['full_name']?></p>
                        </li>

                        <div class="nav-btns">
                            <?php if ($buyerId === $userID AND $status == 'active'){?>
                            <button class="btn btn-success" id="btnedit"><i class="fa-solid fa-gear"></i></button>
                            <button class="btn btn-danger" data-id-annonce="<?=$Id?>" id="btndelete"><i
                                    class="fa-solid fa-trash"></i></button>
                            <?php }else if($status == 'supprimer'){?>
                            <button class="btn btn-success" id="btnreannounceD"
                                data-id-annonce="<?=$annonce['id_announcer']?>">
                                <i class="fa-solid fa-arrow-rotate-left"></i>
                            </button>
                            <button class="btn btn-danger" data-id-annonce="<?=$annonce['id_announcer']?>" id="deleteD">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                            <?php }else{?>
                            <button class="btn btn-primary" id="btnMessage"><i class="fa-solid fa-message"></i></button>
                            <button class="btn btn-primary" id="btnTel"><i class="fa-solid fa-phone"></i></button>
                            <?php }?>
                        </div>
                    </ul>

                </div>
            </div>
        </nav>
        <div class="row swipercol" style="height: 100%;">
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    <?php while ($row = $stm2->fetch()) {?>
                    <div class="swiper-slide"><img src="assets/images/<?php echo $row['img'];?>" alt=""></div>
                    <?php }?>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
        <div class="row mt-2 jack123">
            <div class="row titre-announce">
                <?php echo $annonce['titre_annonce']?>
            </div>
            <div class="row announce-infos mt-4">
                <div class="col d-flex">
                    <i class="fa-solid fa-location-dot"></i>&nbsp;&nbsp;
                    <p> <?php echo $annonce['villename']?></p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <i class="fa-solid fa-clock"></i>&nbsp;&nbsp;
                    <p><?php echo $annonce['created_at'];?></p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <i class="fa-solid fa-eye"></i>&nbsp;&nbsp;
                    <p><?php echo $views?></p>
                </div>
            </div>
            <div class="row mt-3">
                <div class="row" style="width: 50%;">
                    <div class="col" style="color: #555555; border-right: 1px solid #555555;">
                        État
                    </div>
                    <div class="col">
                        <?php echo $annonce['etat']?>
                    </div>
                </div>

                <div class="row" style="width: 50%;">
                    <div class="col" style="color: #555555; border-right: 1px solid #555555;">
                        Prix
                    </div>
                    <div class="col">
                        <?php echo $annonce['prix'] . ' DH'?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row jack123 mt-5">
            <div class="row mode-title">
                Description
            </div>
            <p class="d-flex flex-wrap" style="height: auto; "><?php echo $annonce['description']?>
            </p>
        </div>
    </div>
    <p class="text-muted text-center mt-3">crowd Shoppe n’est pas responsable des produits proposés dans les annonces.
    </p>
    <?php if ($buyerId === $userID){?>
    <div class="container mt-3 mb-5 p-2 rounded shadow">
        <div class="row">
            <div class="col">
                <h5 class="ms-4">vues par jours</h5>
                <div id="chartdiv" style="height: 370px; width: 100%;"></div>
            </div>
            <div class="col-2 d-flex flex-column " style="font-family: 'Rubik', sans-serif;">
                <div class="row mt-4">
                    Listée à <?php echo $annonce['villename']?>
                </div>
                <div class="row mt-3">
                    Listée le <?php echo $annonce['created_at'];?>
                </div>
                <div class="row mt-3">
                    <div class="col d-flex">
                        <?php echo $views?> Cliques
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col d-flex">
                        <?=$mss?> Messages
                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>
    <?php }?>
    <!--footer-->
    <?php include 'footer.php'?>
    <!--end footer-->
    <!-- modal -->
    <div class=" modal-overlay">
        <div class="modal">

            <a class="close-modal">
                <svg viewBox="0 0 20 20">
                    <path fill="#000000"
                        d="M15.898,4.045c-0.271-0.272-0.713-0.272-0.986,0l-4.71,4.711L5.493,4.045c-0.272-0.272-0.714-0.272-0.986,0s-0.272,0.714,0,0.986l4.709,4.711l-4.71,4.711c-0.272,0.271-0.272,0.713,0,0.986c0.136,0.136,0.314,0.203,0.492,0.203c0.179,0,0.357-0.067,0.493-0.203l4.711-4.711l4.71,4.711c0.137,0.136,0.314,0.203,0.494,0.203c0.178,0,0.355-0.067,0.492-0.203c0.273-0.273,0.273-0.715,0-0.986l-4.711-4.711l4.711-4.711C16.172,4.759,16.172,4.317,15.898,4.045z">
                    </path>
                </svg>
            </a><!-- close modal -->

            <div class="modal-content border-0">
                <div class="containerr">
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
            <div class="title-alert d-flex justify-content-center mt-2">Do You want to remove this
                annonce? </div>
            <div class="d-flex justify-content-end mt-3">
                <button class="btn btn-outline-danger me-3 btn_remove_card">Remove</button>
                <button class="btn btn-outline-primary me-3 btn_close">Cancel</button>
            </div>
        </div>
    </div>
    <script>
    $("#btnTel").click(function() {
        Swal.fire({
            position: 'center',
            icon: 'info',
            title: '+212' + <?=$num?>,
            showConfirmButton: true,
        })
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

    $(".menuToggle").click(function() {
        $(".navigation").toggleClass("active");
    })

    $(".fa-message").click(function() {
        let infoId = <?php echo $idinfos ?>;
        let userId = <?php echo $userID  ?>;
        $(location).attr('href', 'sendMessage.php?id=' + userId + '&infoId=' + infoId);
    })

    function toggleModal() {
        $(".modall").toggleClass("show-modall");
    }

    var id_annonce;
    $("#btndelete").click(function() {
        id_annonce = $(this).attr("data-id-annonce");
        toggleModal()
    })

    $("#btnedit").click(function() {
        let infoId = <?=$Id?>;

        $(location).attr('href', 'updateannouncee.php?id=' + infoId);
    })


    $(".close-buttonn").click(function() {
        toggleModal()
    });

    $(".btn_close").click(function() {
        toggleModal()
    });

    $(window).click(function(event) {
        if (event.target == $(".modall")) {
            toggleModal()
        }
    });

    $(".btn_remove_card").click(function() {
        $.post("app/traitannonce.php", {
            id: id_annonce
        }, (data) => {
            if (data == true) {
                toggleModal();

                $(".all456").toggleClass("show")

                if ($(".all456").hasClass("show")) {
                    setTimeout(function() {
                        $(".all456").removeClass("show")
                    }, 1000)
                }

                history.back()
            }
        })
    })




    //if the announce is his one 
    <?php if ($buyerId === $userID){?>
    //states
    am5.ready(function() {

        // Create root element
        // https://www.amcharts.com/docs/v5/getting-started/#Root_element
        var root = am5.Root.new("chartdiv");


        // Set themesDecember
        // https://www.amcharts.com/docs/v5/concepts/themes/
        root.setThemes([
            am5themes_Animated.new(root)
        ]);


        // Create chart
        // https://www.amcharts.com/docs/v5/charts/xy-chart/
        var chart = root.container.children.push(am5xy.XYChart.new(root, {
            panX: true,
            panY: true,
            wheelX: "panX",
            wheelY: "zoomX",
            pinchZoomX: true
        }));

        // Add cursor
        // https://www.amcharts.com/docs/v5/charts/xy-chart/cursor/
        var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
        cursor.lineY.set("visible", false);


        // Create axes
        // https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
        var xRenderer = am5xy.AxisRendererX.new(root, {
            minGridDistance: 30
        });
        xRenderer.labels.template.setAll({
            rotation: -90,
            centerY: am5.p50,
            centerX: am5.p100,
            paddingRight: 15
        });

        var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
            maxDeviation: 0.3,
            categoryField: "date",
            renderer: xRenderer,
            tooltip: am5.Tooltip.new(root, {})
        }));

        var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
            maxDeviation: 0.3,
            renderer: am5xy.AxisRendererY.new(root, {})
        }));


        // Create series
        // https://www.amcharts.com/docs/v5/charts/xy-chart/series/
        var series = chart.series.push(am5xy.ColumnSeries.new(root, {
            name: "Views/day",
            xAxis: xAxis,
            yAxis: yAxis,
            valueYField: "views",
            sequencedInterpolation: true,
            categoryXField: "date",
            tooltip: am5.Tooltip.new(root, {
                labelText: "{valueY}"
            })
        }));

        series.columns.template.setAll({
            cornerRadiusTL: 5,
            cornerRadiusTR: 5
        });

        series.columns.template.adapters.add("fill", function(fill, target) {
            return chart.get("colors").getIndex(series.columns.indexOf(target));
        });

        series.columns.template.adapters.add("stroke", function(stroke, target) {
            return chart.get("colors").getIndex(series.columns.indexOf(target));
        });

        <?php $json_format = graph($conn, $idannounce)?>
        var data = <?=$json_format?>;
        console.log(data);

        xAxis.data.setAll(data);
        series.data.setAll(data);


        // Make stuff animate on load
        // https://www.amcharts.com/docs/v5/concepts/animations/
        series.appear(1000);
        chart.appear(1000, 100);

    });
    // end am5.ready()
    <?php }?>

    $("#btnreannounceD").click(function() {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })

        swalWithBootstrapButtons.fire({
            title: 'Paid operation',
            text: "Paid to get a re-announce",
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Paid',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {

                Swal.fire({
                    title: 'Via Paypal',
                    html: `<div id="smart-button-container">
              <div style="text-align: center;">
                <div id="paypal-button-container"></div>
              </div>
            </div>`,
                })

                function initPayPalButton() {
                    paypal.Buttons({
                        style: {
                            shape: 'rect',
                            color: 'gold',
                            layout: 'vertical',
                            label: 'paypal',
                        },

                        createOrder: function(data, actions) {
                            return actions.order.create({
                                purchase_units: [{
                                    "amount": {
                                        "currency_code": "USD",
                                        "value": 15
                                    }
                                }]
                            });
                        },

                        onApprove: function(data, actions) {
                            return actions.order.capture().then(function(
                                orderData) {

                                // Full available details
                                console.log('Capture result', orderData,
                                    JSON
                                    .stringify(orderData, null, 2));

                                // Show a success message within this page, e.g.
                                const element = document.getElementById(
                                    'paypal-button-container');
                                element.innerHTML = '';
                                element.innerHTML =
                                    '<h3>Thank you for your payment!</h3>';

                                // Or go to another URL:  actions.redirect('thank_you.html');

                            });
                        },

                        onError: function(err) {
                            console.log(err);
                        }
                    }).render('#paypal-button-container');
                }
                initPayPalButton();

            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                    'Cancelled',
                    'info'
                )
            }
        })
    })

    $("#deleteD").click(function() {
        Swal.fire({
            title: 'Delete definitively',
            showDenyButton: true,
            showCancelButton: false,
            denyButtonText: `Delete`,
            confirmButtonText: 'Cancel',
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isDenied) {
                $.post("app/traitannonce.php", {
                    delete: 'delete',
                    id: <?=$Id?>
                }, (data) => {
                    Swal.fire({
                        icon: 'success',
                        showCancelButton: false,
                    })
                    setTimeout(function() {
                        history.back();
                    }, 1500)
                })
            }
        })
    })
    </script>
</body>

</html>
