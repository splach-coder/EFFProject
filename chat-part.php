<?php
    session_start();
    include_once 'app/db.php';
    include_once 'assets/php/functions.php';
    $buyer = $_SESSION['user_id'];
    $seller = -1;
    $anonnce = -1;
    $name = "";
    $pic = getSingleValue($conn, "SELECT picture FROM `users` u, `informations` i WHERE u.id_infos = i.id and u.id = $buyer");
    if(isset($_GET['chat']) && isset($_GET['announce'])){
        $seller = $_GET['chat'];
        $anonnce = $_GET['announce'];
        $name = getSingleValue($conn, "SELECT i.`full_name` FROM `users` u, `informations` i WHERE u.id_infos = i.id and u.id = $seller");
        $annonceName = getSingleValue($conn, "SELECT i.titre_annonce FROM `announcer` a, `infos_generales` i WHERE a.id_infos_Generales = i.id_infos_Generales AND i.id_infos_Generales = $anonnce"); 
        $sellerPic = getSingleValue($conn, "SELECT picture FROM `users` u, `informations` i WHERE u.id_infos = i.id and u.id = $seller");
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>

    <!--Links-->
    <?php include_once 'envirmentLinks.php'?>

    <!--Private Links-->
    <link rel="stylesheet" href="assets/css/FirstPageStyle.css">
    <link href="https://fonts.googleapis.com/css?family=Medula+One" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,500;1,400&display=swap" rel="stylesheet">

    <!-- css  -->
    <link rel="stylesheet" href="assets/css/chat-styl.css">

    <style>
    #back {
        font-size: 25px;
        cursor: pointer;
        color: #333;
        position: relative;
        top: 10px;
        left: -30px;
    }

    #back:hover {
        background: #9E9E9E;
        color: #6C63FF;
    }

    </style>
</head>

<body>
    <div class="container">
        <!-- ========= left side ========== -->
        <div class="left-side">
            <div class="header">
                <ul class="nav-icons">
                    <i class="fa-solid fa-arrow-left-long" id="back"
                        onclick="$(location).attr('href', 'firstpage.php')"></i>
                </ul>

                <div class="user-imgBx">
                    <img src="assets/images/<?php echo $pic?>" alt="">
                </div>
            </div>

            <div class="search-chat">
                <input type="text" id="search-chat" placeholder=" Search or start new chat">
                <ion-icon name="search-outline" id="search-chat-btn"></ion-icon>
            </div>
            <div class="row seperates">
                <div class="col d-flex justify-content-center">
                    <button class="modevente btn active">En Vente</button>
                </div>
                <div class="col d-flex justify-content-center">
                    <button class="modeachat btn">Achats</button>
                </div>
            </div>
            <div class="chat-list">

            </div>
        </div>

        <!-- ========= right side ========== -->
        <div class="right-side">
            <?php if(!isset($_GET['chat'])){?>
            <?php }else{?>
            <div class="header pt-1">
                <div class="user-details">
                    <div class="user-imgBx">
                        <img src="assets\images\<?php echo $sellerPic?>" alt="">
                    </div>
                    <h4 id="chat-name">
                        <a href="gallery.php?id=<?php echo $anonnce?>"> <?php echo $annonceName?> </a>
                        <br>
                        <!--span>Online</span-->
                    </h4>
                </div>

                <ul class=" nav-icons">
                    <li>
                        <ion-icon name="search-outline"></ion-icon>
                    </li>
                    <li>
                        <ion-icon name="ellipsis-vertical"></ion-icon>
                    </li>
                </ul>
            </div>

            <div class="chatBx">
                <?php  $data = $conn->query("SELECT `from_user`, `msg`, DATE_FORMAT(created_at, '%H:%i') as 'time' FROM `chat_table` WHERE (`from_user` = $buyer OR `to_user` = $buyer) AND `id_announce` = $anonnce 
                AND (`from_user` = $seller OR `to_user` = $seller)
                ORDER BY created_at asc;"); ?>
                <?php  while($dt = $data->fetch(PDO::FETCH_ASSOC)){?>
                <div class="msg <?php echo ($dt['from_user'] == $buyer) ? 'msg-me' : 'msg-frnd' ?>">
                    <p>
                        <?php echo $dt['msg']?> <br> <span><?php echo $dt['time']?></span>
                    </p>
                </div>
                <?php }?>
            </div>

            <div class="chat-input">
                <ion-icon name="happy-outline"></ion-icon>
                <ion-icon name="attach-outline"></ion-icon>
                <input type="text" id="chat-place" placeholder="Type a messsage">
                <ion-icon style="color: #6C63FF;" name="send" onclick="showUser($('#chat-place').val())">
                </ion-icon>
            </div>
            <?php }?>
        </div>
    </div>


    <!-- icons -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js">
    </script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

    <script>
    var id = <?php echo $seller?>,
        id_an = <?php echo $anonnce?>;




    $(document).ready(function() {

        $(".chatBx").animate({
            scrollTop: $(document).height() * $(document).height()
        }, 7000);

        //dry("vente");
        dry("vente").then((data) => {
            $(".chat-list").empty();
            $(".chat-list").append(data);


            $(".chat").click(function() {
                id = $(this).attr('data-id-user');
                id_an = $(this).attr('data-id-announce');
                $(location).attr('href',
                    'chat-part.php?chat=' + id +
                    '&announce=' + id_an);
                $(".chat").each(function() {
                    $(this).removeClass("active");
                })
                $(this).addClass("active");
            })
        });


        $(".chat").click(function() {
            id = $(this).attr('data-id-user');
            id_an = $(this).attr('data-id-announce');
            $(location).attr('href',
                'chat-part.php?chat=' + id +
                '&announce=' + id_an);
            $(".chat").each(function() {
                $(this).removeClass("active");
            })
            $(this).addClass("active");
        })


        $(".modevente").click(function() {
            $(this).toggleClass("active");
            $(".modeachat").removeClass("active");

            //ajax request
            //dry("vente");
            dry("vente").then((data) => {
                $(".chat-list").empty();
                $(".chat-list").append(data);


                $(".chat").click(function() {
                    id = $(this).attr('data-id-user');
                    id_an = $(this).attr('data-id-announce');
                    $(location).attr('href',
                        'chat-part.php?chat=' + id +
                        '&announce=' + id_an);
                    $(".chat").each(function() {
                        $(this).removeClass("active");
                    })
                    $(this).addClass("active");
                })
            });
        })

        $(".modeachat").click(function() {
            $(this).toggleClass("active");
            $(".modevente").removeClass("active");

            //ajax request
            dry("achat").then((data) => {
                $(".chat-list").empty();
                $(".chat-list").append(data);



                $(".chat").click(function() {
                    id = $(this).attr('data-id-user');
                    id_an = $(this).attr('data-id-announce');
                    $(location).attr('href',
                        'chat-part.php?chat=' + id +
                        '&announce=' + id_an);
                    $(".chat").each(function() {
                        $(this).removeClass("active");
                    })
                    $(this).addClass("active");
                })
            });

        })


        $('#chat-place').on("keypress", function(e) {
            // If the user presses the "Enter" key on the keyboard
            if (e.key === "Enter") {
                if ($('#chat-place').val() != "")
                    showUser($('#chat-place').val())
            }
        });
    });

    function showUser(str) {
        if (str != "") {
            $.ajax({
                url: "app/msg.php", // Url of backend (can be python, php, etc..)
                type: "POST", // data type (can be get, post, put, delete)
                data: {
                    msg: str,
                    user: <?php echo $buyer?>,
                    chat: id,
                    announce: id_an
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

            if ($(".modeachat").hasClass('active')) {
                dry("achat").then((data) => {
                    $(".chat-list").empty();
                    $(".chat-list").append(data);



                    $(".chat").click(function() {
                        id = $(this).attr('data-id-user');
                        id_an = $(this).attr('data-id-announce');
                        $(location).attr('href',
                            'chat-part.php?chat=' + id +
                            '&announce=' + id_an);
                        $(".chat").each(function() {
                            $(this).removeClass("active");
                        })
                        $(this).addClass("active");
                    })
                });
            } else {
                dry("vente").then((data) => {
                    $(".chat-list").empty();
                    $(".chat-list").append(data);



                    $(".chat").click(function() {
                        id = $(this).attr('data-id-user');
                        id_an = $(this).attr('data-id-announce');
                        $(location).attr('href',
                            'chat-part.php?chat=' + id +
                            '&announce=' + id_an);
                        $(".chat").each(function() {
                            $(this).removeClass("active");
                        })
                        $(this).addClass("active");
                    })
                });
            }

            $(".chatBx").animate({
                scrollTop: $(document).height() + 50000
            }, 7000);
        }
    }

    //function dry(vente) {
    /*$.ajax({
        url: "", // Url of backend (can be python, php, etc..)
        type: "GET", // data type (can be get, post, put, delete)
        data: {
            chat: id,
            vente: vente
        }, // data in json format
        async: false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)
        success: function(response, textStatus, jqXHR) {
            $(".chat-list").empty();
            $(".chat-list").append(response);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });*/


    function dry(vente) {
        return new Promise(function(resolve, reject) {
            $.ajax({
                method: "POST",
                url: "app/chat-user.php",
                data: {
                    chat: id,
                    vente: vente
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

    //}


    $("#search-chat").on("keyup", function(e) {
        var val = $(this).val();
        if (val == "") {
            if ($(".modeachat").hasClass('active')) {
                dry("achat");
            } else {
                dry("vente");
            }
        } else {
            var chat = search_to_load(searchdata(val, loadarray()));
            $(".chat-list").empty();
            $(".chat-list").append(chat);
        }
    })




    function search_to_load(data) {
        var f = []
        for (let i = 0; i < data.length; i++) {
            $(".chat").each(function() {
                ($(this).find('.name').text() == data[i]) ? f.push($(this)): null;
            })
        }
        return f
    }

    function loadarray() {
        var users = [];
        $(".chat").each(function() {
            users.push($(this).find('.name').text());
        })
        return users;
    }

    function searchdata(value, data) {
        var f = []
        for (let i = 0; i < data.length; i++) {
            value = value.toLowerCase();
            let name = data[i].toLowerCase();
            if (name.includes(value)) {
                f.push(data[i]);
            }
        }
        return f
    }
    </script>

</body>

</html>
