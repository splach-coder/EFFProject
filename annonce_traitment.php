<?php 
    session_start();
    require_once 'app/db.php';
    include 'assets/php/functions.php';
    $CaId = $_GET['id'];
    $fullname = "";
    $email = "";
    $tel = "";
    $pass = "";
    if(isset($_SESSION['user_id'])  &&  isset($_SESSION['user_id_infos'])  &&  isset($_SESSION['user_email'])){
        $userId = $_SESSION['user_id'];
        $stmt = $conn->prepare("SELECT full_name, telephone, email, password FROM `informations` i, `users` u WHERE i.id = u.id_infos AND u.id = ?");
        $stmt->execute([$userId]); 
        $user = $stmt->fetch();
        $fullname = $user["full_name"];
        $email = $user["email"];
        $tel = $user["telephone"];
        $pass = $user["password"];
    }
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
    <link rel="stylesheet" href="assets/css/Annoncer.css">
    <link href="https://fonts.googleapis.com/css?family=Medula+One" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,500;1,400&display=swap" rel="stylesheet">


    <script src="assets/javascript/annoncee.js" defer>
    </script>
    <script src="https://www.paypal.com/sdk/js?client-id=test&currency=USD">
    </script>

    <style>
    @import url('https://fonts.googleapis.com/css2?family=Rubik:wght@400&display=swap');

    .cons {
        font-family: 'Rubik', sans-serif;
        font-weight: 400;
        font-size: 16px;
        Color: #4a4a4a;
    }

    .mil .top .title {
        font-size: 20px;
    }

    .cons {
        width: 500px;
        display: flex;
        align-items: flex-start;
        padding: 12px;
        border-radius: 4px;
        border-width: 1px 1px 1px 4px;
        border-style: solid;
        border-color: rgb(46, 107, 255);
        border-image: initial;
        color: rgb(74, 74, 74);
        background-color: rgb(234, 240, 255);
    }

    .top {
        display: flex;
        justify-content: space-between;
    }

    .top .title {
        margin-top: 15px;
    }

    .top i {
        cursor: pointer;
    }

    .iconx {
        width: 10%;
        font-size: 25px;
        color: #2E6BFF;
    }

    .mil {
        width: 100%;
    }

    .mil .bottom ul li {
        margin-bottom: 10px;
    }

    .select-menuu {
        margin-right: 100px;
    }

    </style>
</head>

<body>
    <!--Nav Bar-->
    <?php include_once 'normalNavbar.php'?>


    <div class="container my-5">
        <!-- Progress bar -->
        <div class="progressbar">
            <div class="progress" id="progress"></div>
            <div class="progress-step progress-step-active" data-title="Commence par l’essentiel"></div>
            <div class="progress-step" data-title="Décrivez-nous votre bien"></div>
            <div class="progress-step" data-title="Images"></div>
            <div class="progress-step" data-title="Vos informations"></div>
        </div>
        <div class="form">
            <!-- Steps -->
            <div class="form-step form-step-active">
                <p class="title">INFORMATIONS GÉNÉRALES</p>
                <div class="notice"><i style="color: firebrick;" class="fa-solid fa-circle-exclamation me-2"></i>les
                    champs avec (<span style="color: firebrick;">*</span>) sont obligatoires</div>
                <div class="input-group">
                    <select name="cats" id="cat">
                        <?php  $data = $conn->query("SELECT * FROM `categories`"); ?>
                        <?php  while($dt = $data->fetch(PDO::FETCH_ASSOC)){?>
                        <option value="<?php echo $dt['id_Cat']?>"><?php echo $dt['Name_Cat']?></option>
                        <?php  }?>
                    </select>
                    <span class="err"><i class="fa-solid fa-circle-exclamation"></i> Veuillez renseigner ce champ</span>
                </div>
                <div class=" input-group">
                    <label for="vls"><span style="color: firebrick;">*</span> Villes</label>
                    <select name="villes" id="vls">
                        <option value="" selected>Tapez la ville</option>
                        <?php  $data = $conn->query("SELECT * FROM `ville`"); ?>
                        <?php  while($dt = $data->fetch(PDO::FETCH_ASSOC)){?>
                        <option value="<?php echo $dt['id_Ville']?>">
                            <?php echo $dt['villename']?>
                        </option>
                        <?php }?>
                    </select>
                    <span class="err"><i class="fa-solid fa-circle-exclamation"></i> Veuillez renseigner ce champ</span>
                </div>
                <div class="input-group">
                    <label for="trans"><span style="color: firebrick;">*</span> Type de transaction</label>
                    <select name="" id="trans">
                        <option value="">Choissisez</option>
                        <option value="1" selected>Vente</option>
                        <option value="2">Demande</option>
                    </select>
                    <span class="err"><i class="fa-solid fa-circle-exclamation"></i> Veuillez renseigner ce champ</span>
                </div>
                <div class="btns-group d-flex justify-content-end">
                    <a href="#" class="btn btn-next width-50 ml-auto">Next</a>
                </div>
            </div>
            <div class="form-step ">
                <p class="title">DESCRIPTION DU BIEN</p>
                <div class="input-group row" style="width: 100%;">
                    <div class="col">
                        <label for="title"><span style="color: firebrick;">*</span> Titre D'annonce</label>
                        <input type="text" name="annonceTitle" id="title" />
                        <span class="err"><i class="fa-solid fa-circle-exclamation"></i> Minimum 10 caractères</span>
                    </div>
                    <div class="col">
                        <label for="prix">Prix</label>
                        <input type="text" name="Prix" id="prix" placeholder="0" value="0" />
                    </div>
                </div>
                <div class="input-group">
                    <label for="textarea"><span style="color: firebrick;">*</span> Texte de l’annonce</label>
                    <textarea name="description" id="desc" cols="30" rows="6"></textarea>
                    <span class="err"><i class="fa-solid fa-circle-exclamation"></i> Minimum 10 caractères</span>
                </div>
                <div class="btns-group d-flex justify-content-between">
                    <a href="#" class="btn btn-prev">Previous</a>
                    <a href="#" class="btn btn-next">Next</a>
                </div>
            </div>
            <div class="form-step ">
                <div class="input-group d-flex" style="width: 100%;">
                    <!--Drag and Drop-->
                    <form id="imageDropzone" method="POST" action="app/Upload.php" enctype="multipart/form-data"
                        class="dropzone d-flex align-items-center justify-content-center">
                        <div
                            class="dz-message d-flex justify-content-center align-content-center flex-column border py-5 px-2 rounded ">
                            <input type="file" id="file" name="image[]" multiple class="inputfile"
                                data-multiple-caption="{count} files selected" />
                            <i class="fa-solid fa-cloud fs-3 m-auto" style="color: #6C63FF;"></i>
                            <label for="file">Upload images here <span id="s"></span> </label>
                        </div>

                        <input type="submit" id="sub" class="d-none" name="submit" value="submit">

                        <div class="cons ms-5">
                            <div class="iconx"><i class="fa-solid fa-circle-exclamation"></i></div>
                            <div class="mil">
                                <div class="top">
                                    <div class="title">Conseils</div>
                                    <i class="fa-solid fa-xmark"></i>
                                </div>
                                <div class="bottom">
                                    <ul>
                                        <li>Une annonce avec photos est 10 fois plus consultée qu'une annonce sans
                                            photos
                                        </li>
                                        <li>Prenez de belles photos bien éclairées.</li>
                                        <li>C'est la première impression qui compte!</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="btns-group d-flex justify-content-between">
                    <a href="#" class="btn btn-prev">Previous</a>
                    <a href="#" class="btn btn-next 123dfs">Next</a>
                </div>
            </div>
            <div class="form-step ">
                <p class="title">VOS INFORMATIONS</p>
                <div class="input-group row" style="width: 100%;">
                    <div class="col">
                        <label for="fullname"><span style="color: firebrick;">*</span> Nom et prénom</label>
                        <input type="text" name="fullname" id="fullname" value="<?php echo $fullname?>" />
                    </div>
                    <div class="col">
                        <label for="email"><span style="color: firebrick;">*</span> Email</label>
                        <input type="email" name="email" id="email" value="<?php echo $email?>" />
                    </div>
                </div>
                <div class="input-group row" style="width: 100%;">
                    <div class="col">
                        <label for="tel"><span style="color: firebrick;">*</span> Telephone</label>
                        <input type="text" name="text" id="tel" value="<?php echo $tel?>" />
                    </div>
                    <div class="col">
                        <label for="pass"><span style="color: firebrick;">*</span> Password</label>
                        <input type="password" name="pass" id="pass" value="<?php echo $pass?>" />
                    </div>
                </div>
                <div class="btns-group d-flex justify-content-between">
                    <a href="#" class="btn btn-prev ">Previous</a>
                    <input type="button" name="submit" value="Submit" id="createAnnonce" class="btn btn-submit" />
                </div>
            </div>
        </div>
    </div>

    <!--footer-->
    <?php include 'footer.php'?>
    <!--end footer-->



    <script>
    $(document).ready(function() {
        $('.annoncer').css('display', 'none')

        $('#cat option[value=<?php echo $CaId?>]').attr('selected', '');
        <?php 
        if (isset($_SESSION['user_id']) && isset($_SESSION['user_id_infos']) && isset($_SESSION[
            'user_email'])) {?>
        $("#fullname").prop('disabled', true);
        $("#email").prop('disabled', true);
        $("#pass").prop('disabled', true);
        $("#tel").prop('disabled', true);
        <?php } ?>


        $(".menuToggle").click(function() {
            $(".navigation").toggleClass("active");
        })
        <?php 
        if (isset($_SESSION['user_id']) && isset($_SESSION['user_id_infos']) && isset($_SESSION[
            'user_email'])) {?>
        $("#createAnnonce").click(function() {
            let cat = $("#cat option:selected").val();
            let city = $("#vls option:selected").val();
            let title = $("#title").val();
            let prix = $("#prix").val();
            let desc = $("#desc").val();

            $.post("app\\announcer.php", {
                cat: cat,
                city: city,
                title: title,
                prix: prix,
                desc: desc,
                login: true
            }, (data) => {
                if (data == 'test') {

                    Swal.fire({
                        title: 'Congrats',
                        text: "your announce will be available soon",
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1950
                    })

                    setTimeout(function() {
                        $("#sub").click();
                    }, 2000)

                } else {
                    Swal.fire({
                        text: data,
                        icon: 'error'
                    })
                }
            })

        }) <?php }else{ ?>
        $("#createAnnonce").click(function() {
            let cat = $("#cat option:selected").val();
            let city = $("#vls option:selected").val();
            let title = $("#title").val();
            let prix = $("#prix").val();
            let desc = $("#desc").val();
            let name = $("#fullname").val();
            let email = $("#email").val();
            let pass = $("#pass").val();
            let tel = $("#tel").val();

            $.post("app\\announcer.php", {
                cat: cat,
                city: city,
                title: title,
                prix: prix,
                desc: desc,
                login: 'false',
                name: name,
                email: email,
                pass: pass,
                tel: tel

            }, (data) => {
                if (data == "announcer") {
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: data,
                        showConfirmButton: false,
                        timer: 1950
                    })

                    setTimeout(function() {
                        $("#sub").click();
                    }, 2000)

                } else {
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: data,
                        showConfirmButton: false,
                        timer: 1100
                    })
                }
            })

        }) <?php }?>


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
                            }, (dt) => {

                            })
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
                    Swal.fire(text).then((result) => {
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

    })
    </script>
</body>

</html>
