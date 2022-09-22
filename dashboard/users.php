<?php 
    session_start();
    require_once '../app/db.php';
    include '../assets/php/functions.php';

    if(isset($_SESSION['user_id'])  &&  isset($_SESSION['user_id_infos'])  &&  isset($_SESSION['user_email']) && $_SESSION['role'] == 'admin'){
        $params = "ORDER BY u.id DESC";
        if(isset($_GET['filter'])){
            if($_GET['filter'] == 'asc')
                $params = "ORDER BY u.id ASC";                
        }
  
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/man-style.css" rel="stylesheet">
    <link href="css/dss-bd.css" rel="stylesheet">
    <link href="css/filter.css" rel="stylesheet">

    <?php include 'links.php';?>

    <style>
    .table .info {
        display: flex;
        align-items: center;
    }

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
    }

    .srch-input {
        display: flex;
        align-items: center;
        margin-right: 15px;
        position: relative;
        background-color: #f5f5f5;
        border-radius: 15px;
        padding: 0px 10px;
        height: 45px;

    }

    .srch-input i {
        font-size: 18px;
        color: #6C63FF;
        margin-right: 10px;
        opacity: .9;
    }

    .srch-input input {
        color: #4D5465;
        font-size: 18px;
        border: none;
        outline: none;
        background-color: transparent;
    }

    </style>
</head>

<body>
    <?php include 'sidemenu.php';?>

    <section class="home" style="margin-bottom: 50px;">
        <div class="text">Users</div>
        <div class="filter123">
            <div class="srch-input">
                <i class="fas fa-search"></i>
                <input type="text" id="srch" name="search" placeholder="search...">
            </div>
            <div class="select-menu">
                <div class="select-btn">
                    <span class="option-text">Filter</span>
                    <i class="fa-solid fa-filter" id="filter32"></i>
                </div>
                <ul class="options">
                    <li class="option" data-type="asc">
                        <i class="fa-solid fa-arrow-down-1-9" style="color: #171515;"></i>
                        <span class="option-text">1-9</span>
                    </li>
                    <li class="option" data-type="desc">
                        <i class="fa-solid fa-arrow-down-9-1" style="color: #171515;"></i>
                        <span class="option-text">9-1</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="table" style="width:90% !important; margin:0 auto;">
            <div class="header">
                <span style="width:5%; ">Id</span>
                <span style="width:20%;">Name</span>
                <span style="width:25%;">Email</span>
                <span style="width:15%;">Telephone</span>
                <span style="width:15%;">Ville</span>
                <span style="width:10%;"></span>
                <span style="width:10%;"></span>
                <span style="width:10%;"></span>
            </div>
            <?php  $data = $conn->query("SELECT u.id, i.full_name, u.email, i.telephone, v.villename FROM `users` u, `informations` i, `ville` v
            WHERE u.id_infos = i.id
            AND i.id_Ville = v.id_Ville
            AND u.role = 'user'
            $params"); ?>
            <?php  while($dt = $data->fetch(PDO::FETCH_ASSOC)){?>
            <div class="info">
                <span style="width:5%; "><?=$dt['id']?></span>
                <span style="width:20%;"><?=$dt['full_name']?></span>
                <span style="width:25%;"><?=$dt['email']?></span>
                <span style="width:15%;"><?=$dt['telephone']?></span>
                <span style="width:15%;"><?=$dt['villename']?></span>
                <span style="width:10%; display: flex; justify-content: center;"><a
                        href="viewuser.php?id=<?=$dt['id']?>"
                        style="  padding:10px 20px; text-decoration: none; background: #6C63FF; color: #f5f5f5; border-radius: 7px; cursor: pointer;font-size: 18px;">view</a></span>
                <span style="width:10%;  display: flex; justify-content: center;"><button data-user-id="<?=$dt['id']?>"
                        class="sendMsg" type="button"
                        style=" width:50%; cursor: pointer; padding:10px 20px !important; background: #0191D8; color: #f5f5f5; border-radius: 7px; border: none; font-size: 18px; display: flex; justify-content: center; align-items: center; height: 45px;"><i
                            class="fas fa-message"></i></button></span>
                <span style="width:10%;  display: flex; justify-content: center;"><a
                        href="userstate.php?id=<?=$dt['id']?>" class="state" type="button"
                        style="text-decoration: none;width:50%; cursor: pointer; padding:10px 20px !important; background: #0191D8; color: #f5f5f5; border-radius: 7px; border: none; font-size: 18px; display: flex; justify-content: center; align-items: center; height: 45px;"><i
                            class="fa-solid fa-chart-line"></i></a></span>
                <span style="width:10%;  display: flex; justify-content: center;"><button data-user-id="<?=$dt['id']?>"
                        class="delete" type="button"
                        style=" width:50%; cursor: pointer; padding:10px 20px !important; background: #FF4949; color: #f5f5f5; border-radius: 7px; border: none; font-size: 18px; display: flex; justify-content: center; align-items: center; height: 45px;"><i
                            class="fas fa-trash"></i></button></span>
            </div>
            <?php }?>
        </div>
    </section>
    <script>
    $(document).ready(function() {
        <?php $json = getusers($conn, $params)?>
        const users = <?=$json?>;

        $("#srch").on('keyup', function() {
            let value = $(this).val()
            var data = searchdata(value, users)
            loadCards(data)
            $(".sendMsg").click(async function() {
                const {
                    value: text
                } = await Swal.fire({
                    input: 'textarea',
                    inputLabel: 'Message',
                    inputPlaceholder: 'Type your message here...',
                    inputAttributes: {
                        'aria-label': 'Type your message here'
                    },
                    showCancelButton: true
                })

                if (text) {
                    $.ajax('back-end/send-msg.php', {
                        method: 'POST',
                        data: {
                            id: $(this).attr("data-user-id"),
                            msg: text
                        },
                        timeout: 5000
                    }).then(function(response) {
                        if (response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'send Successfully',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    }).catch(function(err) {
                        console.log('Caught an error:' + err.statusText);
                    });
                }
            })
        })

        $(".sendMsg").click(async function() {
            const {
                value: text
            } = await Swal.fire({
                input: 'textarea',
                inputLabel: 'Message',
                inputPlaceholder: 'Type your message here...',
                inputAttributes: {
                    'aria-label': 'Type your message here'
                },
                showCancelButton: true
            })

            if (text) {
                $.ajax('back-end/send-msg.php', {
                    method: 'POST',
                    data: {
                        id: $(this).attr("data-user-id"),
                        msg: text
                    },
                    timeout: 5000
                }).then(function(response) {
                    if (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'send Successfully',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                }).catch(function(err) {
                    console.log('Caught an error:' + err.statusText);
                });
            }
        })


        $(".option").click(function() {
            const tr = $(this).attr("data-type");
            $(location).prop('href', 'users.php?filter=' + tr);
        })

        function modal(id, name, email, telephone, ville) {
            var info = `<div class="info">
                <span style="width:5%; ">${id}</span>
                <span style="width:20%;">${name}</span>
                <span style="width:25%;">${email}</span>
                <span style="width:15%;">${telephone}</span>
                <span style="width:15%;">${ville}</span>
                <span style="width:10%; display: flex; justify-content: center;"><a
                        href="viewuser.php?id=${id}"
                        style="  padding:10px 20px; text-decoration: none; background: #6C63FF; color: #f5f5f5; border-radius: 7px; cursor: pointer;font-size: 18px;">view</a></span>
                <span style="width:10%;  display: flex; justify-content: center;"><button data-user-id="${id}"
                        class="sendMsg" type="button"
                        style=" width:50%; cursor: pointer; padding:10px 20px !important; background: #0191D8; color: #f5f5f5; border-radius: 7px; border: none; font-size: 18px; display: flex; justify-content: center; align-items: center; height: 45px;"><i
                            class="fas fa-message"></i></button></span>
                <span style="width:10%;  display: flex; justify-content: center;"><a
                        href="userstate.php?id=${id}" class="state" type="button"
                        style="text-decoration: none;width:50%; cursor: pointer; padding:10px 20px !important; background: #0191D8; color: #f5f5f5; border-radius: 7px; border: none; font-size: 18px; display: flex; justify-content: center; align-items: center; height: 45px;"><i
                            class="fa-solid fa-chart-line"></i></a></span>
                <span style="width:10%;  display: flex; justify-content: center;"><button data-user-id="${id}"
                        class="delete" type="button"
                        style=" width:50%; cursor: pointer; padding:10px 20px !important; background: #FF4949; color: #f5f5f5; border-radius: 7px; border: none; font-size: 18px; display: flex; justify-content: center; align-items: center; height: 45px;"><i
                            class="fas fa-trash"></i></button></span>
            </div>
            `

            return info;
        }

        function searchdata(value, data) {
            var f = []
            if (value != "") {
                for (let i = 0; i < data.length; i++) {
                    value = value.toLowerCase();
                    let name = data[i].name.toLowerCase()
                    if (name.includes(value)) {
                        f.push(data[i]);
                    }
                }
            } else {
                f = data;
            }

            return f;
        }

        function loadCards(data) {
            $(".table").empty()
            var cardslist = $(".table");
            cardslist.append(`<div class="header">
                <span style="width:5%; ">Id</span>
                <span style="width:20%;">Name</span>
                <span style="width:25%;">Email</span>
                <span style="width:15%;">Telephone</span>
                <span style="width:15%;">Ville</span>
                <span style="width:10%;"></span>
                <span style="width:10%;"></span>
                <span style="width:10%;"></span>
            </div>`);
            for (let i = 0; i < data.length; i++) {
                cardslist.append(modal(data[i].id, data[i].name, data[i].email, data[i].tel, data[i]
                    .ville));
            }
        }
    })




    window.onbeforeunload = (e) => {
        $("#srch").val("");
    };
    </script>
</body>

</html>
<?php }else{
    header("Location: ../firstpage.php");
}?>
