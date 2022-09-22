<?php
    session_start();
    require_once '../app/db.php';
    require '../assets/php/functions.php';


    $announce_time = getSingleValue($conn, "SELECT announce_time FROM params");
    $accept_time = getSingleValue($conn, "SELECT accept_time FROM params");
    $accept_time = $accept_time/1000/60

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!--links-->
    <link href="../assets/css/updateannounce.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/firstPageStyle.css">
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

    .img156 {
        background-color: #F5F5F5 !important;

    }

    .img156 label i {
        display: block !important;
        color: #0000FF !important;
        opacity: 0.8;
        cursor: pointer;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .img156:hover label i {
        opacity: 1;
    }

    </style>
</head>

<body>

    <!--Nav Bar-->
    <?php include 'sidemenu.php'?>
    <section class="home" style="margin-bottom: 50px;">
        <div class="text">Settings</div>
        <div class="container shadow rounded">
            <div class="row " style="width: 100%; height: 100%; color: #666666;">
                <div class="col-3 pt-3 d-flex flex-column align-items-center ">
                    <div class="p-3 row rty active infos" style=" width: 95%; cursor: pointer;">
                        <div class="col ">Generales</div>
                        <div class="col-1 b">
                            <i class="fa-solid fa-chevron-right"></i>
                        </div>
                    </div>
                    <div class="p-3 row mt-3 rty  pass" style=" width: 95%; cursor: pointer;">
                        <div class="col">Ajouter une catégorie</div>
                        <div class="col-1 ">
                            <i class="fa-solid fa-chevron-right"></i>
                        </div>
                    </div>

                </div>
                <div class="col colinfo d-flex flex-column justify-content-center">
                    <div class="row mb-4">
                        <label for="exampleFormControlInput3" class="form-label"> <span class="star">*</span>
                            Announce time</label>
                        <input type="text" class="form-control at pppi" id="exampleFormControlInput3" placeholder="0"
                            name="at" value="<?=$announce_time?>">
                        month
                        <span class="err"><i class="fa-solid fa-circle-exclamation"></i> Veuillez renseigner
                            ce champ</span>
                    </div>
                    <div class="row mb-4">
                        <label for="exampleFormControlInput3" class="form-label"> <span class="star">*</span>
                            Accept time</label>
                        <input type="text" class="form-control act pppi" id="exampleFormControlInput3" placeholder="0"
                            name="act" value="<?=$accept_time?>">
                        min
                        <span class="err"><i class="fa-solid fa-circle-exclamation"></i> Veuillez renseigner
                            ce champ</span>
                    </div>
                    <div class="row ">
                        <button style="width: 230px; height: 45px;" class="btn btn-primary ms-auto" type="button"
                            id="submit" name="submit" disabled>
                            SAUVEGARDER
                        </button>
                    </div>
                </div>
                <div class="col d-none colpass d-flex justify-content-center flex-column">
                    <form>
                        <div class="row mb-4">
                            <label for="exampleFormControlInput3" class="form-label"> <span class="star">*</span>
                                Titre </label>
                            <input type="text" class="form-control title" id="exampleFormControlInput3"
                                placeholder="titre" name="title">
                            <span class="err"><i class="fa-solid fa-circle-exclamation"></i> Veuillez renseigner
                                ce champ</span>
                        </div>
                        <div class="d-flex flex-wrap">
                            <div class="imageBox img156 me-3 d-flex justify-content-center align-items-center">

                                <label for="file"><i class="fa-solid fa-circle-plus"></i></label>
                                <input id="file" type="file" name="image" class="d-none" accept="image/*"
                                    onchange="loadFile(event)">
                                <input type="submit" name="submit" id="sub" class="d-none" value="">

                            </div>
                            <div
                                class="imageBox mb-3 me-3 d-flex flex-column justify-content-center align-items-center">
                                <img src="" alt="" class="d-none" id="output">
                            </div>
                        </div>
                        <div class="row ">
                            <input style="width: 230px; height: 45px;" class="btn btn-primary ms-auto" type="submit"
                                id="submit1" name="submit" value="Ajouter" />
                        </div>
                    </form>
                    <h2 class="mt-4">nos catégories</h2>
                    <div class="cards d-flex flex-wrap overflow-scroll mt-3">
                        <?php  $data = $conn->query("SELECT * FROM `categories`"); ?>
                        <?php  while($dt = $data->fetch(PDO::FETCH_ASSOC)){?>
                        <div class="card mb-3" data-cat-id="<?php echo $dt['id_Cat']?>">
                            <div class="row d-flex justify-content-end">
                                <i class="fas iclose fa-close mt-1" style=" cursor: pointer;"></i>
                            </div>
                            <div class="row image">
                                <img src="../assets/icons/<?php echo $dt['icon']?>" alt="icon">
                            </div>
                            <div class="row title">
                                <p><?php echo $dt['Name_Cat']?></p>
                            </div>
                        </div>
                        <?php }?>
                    </div>
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

    $(".iclose").click(function() {
        const id = $(this).parent().parent().attr("data-cat-id");

        $.post("back-end/removecat.php", {
            id: id,
            ope: 'nbr'
        }, (data) => {
            if (data) {
                Swal.fire({
                    icon: 'error',
                    title: 'this cat have a ' + data + ' announcers related to',
                    showDenyButton: true,
                    showConfirmButton: false,
                    showCancelButton: true,
                    denyButtonText: `Delete it anyway`,
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isDenied) {
                        Swal.fire({
                            title: 'enter your password please',
                            input: 'text',
                            inputAttributes: {
                                autocapitalize: 'off'
                            },
                            showCancelButton: true,
                            confirmButtonText: 'Look up',
                            showLoaderOnConfirm: true,
                            preConfirm: (login) => {
                                $.post("back-end/checkcode.php", {
                                    code: login
                                }, (data) => {
                                    if (data == "matched")
                                        $.post("back-end/removecat.php", {
                                            id: id,
                                            ope: 'delete'
                                        }, (data) => {
                                            if (data ==
                                                "deleted successfully"
                                            ) {
                                                Swal.fire(
                                                    '',
                                                    data,
                                                    'success'
                                                )

                                                setTimeout(function() {
                                                    location
                                                        .reload();
                                                }, 1000)



                                            } else {
                                                Swal.fire(
                                                    'error',
                                                    data,
                                                    'error'
                                                )
                                            }
                                        })
                                    else
                                        Swal.fire("wrong password");
                                })
                            },
                            allowOutsideClick: () => !Swal.isLoading()
                        })
                    }
                })
            }
        })
    })


    $(".pppi").keypress(function() {
        $("#submit").prop("disabled", false);
    })


    $("form").submit(function(e) {

        e.preventDefault();

        var form = new FormData(this);

        $.ajax({
            type: "POST",
            url: "back-end/addcat.php?title=" + $(".title").val(),
            data: form,
            processData: false, //add this
            contentType: false, //and this
            success: function(res) {
                location.reload();
            }
        })

    });

    var loadFile = function(event) {
        var output = document.getElementById('output');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
            URL.revokeObjectURL(output.src) // free memory
        }
        output.classList.remove("d-none");
    };

    $("#submit").click(function() {
        var pass = true;

        $(".pppi").each(function() {
            if ($(this).val() == "") {
                pass = false;
            }
        })

        if (pass) {
            $.ajax({
                url: "back-end/updateparams.php", // Url of backend (can be python, php, etc..)
                type: "POST", // data type (can be get, post, put, delete)
                data: {
                    at: $(".at").val(),
                    act: $(".act").val(),
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
