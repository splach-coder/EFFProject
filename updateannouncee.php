<?php
    session_start();
    require_once 'app/db.php';
    require 'assets/php/functions.php';

    $id = $_GET['id'];

    $sql = "SELECT a.id_announcer, i.id_infos_Generales, a.id_user, i.titre_annonce, i.prix, i.description FROM `infos_generales` i, `announcer` a, `images` im 
    WHERE i.id_infos_Generales = a.id_infos_Generales 
    AND i.id_infos_Generales = im.id_infos_Generales 
    AND a.status = 'active' 
    AND a.id_announcer = $id
    GROUP BY a.id_announcer;";

    $stmt = $conn->query($sql);
    $ance = $stmt->fetch();

    $idinfo = $ance['id_infos_Generales'];

    $imagequery = "SELECT id_image, img FROM `images` WHERE id_infos_Generales = $idinfo";
    $stm2 = $conn->query($imagequery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--Links-->
    <?php include_once 'envirmentLinks.php'?>

    <link rel="stylesheet" href="assets/css/firstPageStyle.css">
    <link rel="stylesheet" href="assets/css/modal.css">
    <link href="https://fonts.googleapis.com/css?family=Medula+One" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,500;1,400&display=swap" rel="stylesheet">


    <link href="assets/css/updateannounce.css" rel="stylesheet">

    <script src="https://www.paypal.com/sdk/js?client-id=test&currency=USD">
    </script>
</head>

<body>

    <!--Nav Bar-->
    <?php include_once 'normalNavbar.php'?>

    <div class="container p-3 rounded d-flex flex-column shadow">
        <div class="row mb-2" style="width: 100%;">
            <div class="col d-flex justify-content-center">
                <h4>Infos</h4>
            </div>
            <div class="col-5 d-flex justify-content-center">
                <h4>Images</h4>
            </div>
        </div>
        <div class="row" style="width: 100%; height: 100%;">
            <div class="col">
                <div class="row mb-3">
                    <label for="titre">Titre</label>
                    <input type="text" id="titre" value="<?=$ance['titre_annonce']?>">
                </div>

                <div class="row mb-3">
                    <label for="titre">Prix</label>
                    <input type="number" id="prix" value="<?=$ance['prix']?>">
                </div>

                <div class="row">
                    <label for="titre">Description</label>
                    <textarea name="desc" id="desc" cols="10" rows="4"><?=$ance['description']?></textarea>
                </div>
            </div>
            <div class="col-5 pt-2">
                <div style="width: 100%; height: 100%;" class="d-flex flex-wrap justify-content-center images">
                    <?php while ($row = $stm2->fetch()) {?>
                    <div class="imageBox mb-3 me-3 d-flex flex-column justify-content-center align-items-center">
                        <img src="assets/images/<?php echo $row['img'];?>" alt="">
                        <i class="fas fa-close icon-close" data-image="<?=$row['img'];?>"
                            id="<?=$row['id_image'];?>"></i>
                    </div>
                    <?php }?>

                    <div class="imageBox img156 me-3 d-flex flex-column justify-content-center align-items-center">
                        <form>
                            <label for="file"><i class="fa-solid fa-circle-plus"></i></label>
                            <input id="file" type="file" name="image" class="d-none">
                            <input type="submit" name="submit" id="sub" class="d-none" value="">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4 d-flex justify-content-center" style="width: 100%;">
            <button class="btn btn-primary py-2" id="btnsauve" style="width: 300px;">SAUVGARDER</button>
        </div>
    </div>

    <!--footer-->
    <?php include 'footer.php'?>
    <!--end footer-->
    <script>
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

    $(".icon-close").click(function() {
        const id = $(this).attr("id");
        const img = $(this).attr("data-image");

        $.post("app/removeimg.php", {
            image: img,
            id: id
        }, (res) => {
            location.reload();
        });
    })


    $("form").submit(function(e) {
        e.preventDefault();

        var form = new FormData(this);

        $.ajax({
            type: "POST",
            url: "app/handleimage.php?id=" + <?=$idinfo?>,
            data: form,
            processData: false, //add this
            contentType: false, //and this
            success: function(res) {
                location.reload();
            }
        })
    });

    $("#file").on("change", function() {
        if ($('.imageBox').length <= 6) {
            $("#sub").click();
        } else {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            })

            swalWithBootstrapButtons.fire({
                title: 'maximum 8 imgs!',
                text: "Paid to get infinite images",
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
                                            "value": 1
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
                        'You have limited images (max: 8) :)',
                        'error'
                    )
                }
            })
        }

        if ($('.imageBox').length <= 9) {
            $('.img156').addClass("d-none");
        } else {
            $('.img156').removeClass("d-none");
        }
    })

    $("#btnsauve").click(function() {
        $.post("app/updateannounce.php", {
                id_info: <?=$idinfo?>,
                title: $("#titre").val(),
                prix: $("#prix").val(),
                desc: $("#desc").val()
            },
            (data) => {
                if (data) {
                    Swal.fire({
                        icon: 'success'
                    })
                }
            })
    })
    </script>
</body>

</html>
</script>
</body>

</html>
