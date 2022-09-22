<?php
    session_start();
    require_once '../app/db.php';
    require '../assets/php/functions.php';

    $id = $_GET['id'];


    $stmtuser = $conn->prepare("SELECT i.full_name, u.id_infos, i.telephone, u.email, i.id_Ville, u.password FROM `users` u, `informations` i
    WHERE u.id_infos = i.id 
    AND u.id = ?");

    $stmtuser->execute([$id]); 
    
    $user = $stmtuser->fetch();
    $idinfo = $user['id_infos']
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
    <?php include 'sidemenu.php'?>
    <section class="home" style="margin-bottom: 50px;">
        <div class="text">Update User</div>
        <div class="container shadow rounded">
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
                            <label for="exampleFormControlInput3" class="form-label"> <span class="star">*</span>
                                Nom</label>
                            <input type="text" class="form-control name pppi" id="exampleFormControlInput3"
                                placeholder="John Doe" name="S_fullname" value="<?=$user['full_name']?>">
                            <span class="err"><i class="fa-solid fa-circle-exclamation"></i> Veuillez renseigner
                                ce champ</span>
                        </div>
                        <div class="row mb-4">
                            <label for="exampleFormControlInput4" class="form-label"> <span class="star">*</span>
                                Téléphone</label>
                            <input type="text" class="form-control tel pppi" id="exampleFormControlInput4"
                                placeholder="06XXXXXXXX" name="S_tel" value="<?=$user['telephone']?>">
                            <span class="err"><i class="fa-solid fa-circle-exclamation"></i> Veuillez renseigner
                                ce
                                champ</span>
                        </div>
                        <div class="row mb-4">
                            <label for="exampleFormControlInput1" class="form-label"> <span class="star">*</span> E-mail
                                address</label>
                            <input type="email" class="form-control email" id="exampleFormControlInput1"
                                placeholder="name@example.com" name="S_email" value="<?=$user['email']?>">
                        </div>

                        <div class="row mb-4">
                            <label for="exampleFormControlInput5" class="form-label"> <span class="star">*</span>
                                Ville</label>
                            <select class="form-select form-control" id="cities" aria-label="Default select example"
                                name="cities">
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
                            <button style="width: 230px; height: 45px;" class="btn btn-primary ms-auto" type="button"
                                id="submit" name="submit" disabled>
                                SAUVEGARDER
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col d-none colpass d-flex justify-content-center">
                    <form class="form mt-3">
                        <div class="row mb-4">
                            <label for="exampleFormControlInput4" class="form-label"> <span class="star">*</span>
                                Mot de passe actuel</label>
                            <input type="text" class="form-control ppps actuel" id="exampleFormControlInput4"
                                placeholder="Mot de passe actuel" value="<?=$user['password']?>">
                            <span class=" err"><i class="fa-solid fa-circle-exclamation"></i> Veuillez
                                renseigner
                                ce
                                champ</span>
                        </div>

                        <div class="row mb-4">
                            <label for="exampleFormControlInput4" class="form-label"> <span class="star">*</span>
                                Nouveau mot de passe</label>
                            <input type="text" class="form-control ppps new" id="exampleFormControlInput4"
                                placeholder="Nouveau mot de passe" ">
                            <span class=" err"><i class="fa-solid fa-circle-exclamation"></i> Veuillez renseigner
                            ce
                            champ</span>
                        </div>

                        <div class="row mb-4">
                            <label for="exampleFormControlInput4" class="form-label"> <span class="star">*</span>
                                Confirmer le mot de passe</label>
                            <input type="text" class="form-control  ppps renew" id="exampleFormControlInput4"
                                placeholder="Confirmer le mot de passe">
                            <span class="err"><i class="fa-solid fa-circle-exclamation"></i> Veuillez renseigner
                                ce
                                champ</span>
                        </div>

                        <div class="row">
                            <button type="button" id="sauvepass" style="width: 230px; height: 45px; cursor: pointer;"
                                class="btn btn-primary ms-auto" disabled>SAUVEGARDER</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>


    <script>
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
                url: "../app/updatemotpass.php", // Url of backend (can be python, php, etc..)
                type: "POST", // data type (can be get, post, put, delete)
                data: {
                    id: <?=$id?>,
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
                        $('.actuel').val($('.new').val())
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
                url: "back-end/updateuserdataA.php", // Url of backend (can be python, php, etc..)
                type: "POST", // data type (can be get, post, put, delete)
                data: {
                    id: <?=$id?>,
                    id_info: <?=$idinfo?>,
                    name: $('.name.pppi').val(),
                    tel: $('.tel').val(),
                    email: $('.email').val(),
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
