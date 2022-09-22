<?php 
    session_start();
    include_once 'app/db.php';

    $err = '';
    if(isset($_GET['error']) && $_GET['error'] === 'all data are required'){
        $err = 'err';
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--Links-->
    <?php include 'envirmentLinks.php'?>

    <!--Private Links-->
    <link rel="stylesheet" href="assets/css/firstPageStyle.css">
    <link rel="stylesheet" href="assets/css/modal.css">
    <link href="https://fonts.googleapis.com/css?family=Medula+One" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/Signup.css">

</head>

<body>
    <!--Nav Bar-->
    <?php include 'normalNavbar.php'?>

    <div class="container d-flex align-items-center flex-column mb-3">

        <?php if(isset($_GET['error'])){?>
        <script>
        Swal.fire(
            "<?=$_GET['error']; ?>"
        );
        </script>
        <?php }?>

        <form class="form-control border-0 p-5" method="POST" action="app/signupBack-end.php">
            <h2 class="display-5 text-center">Créer un compte</h2>
            <div class="row mb-2">
                <label for="exampleFormControlInput3" class="form-label"> <span class="star">*</span> Nom</label>
                <input type="text" class="form-control" id="exampleFormControlInput3" placeholder="John Doe"
                    name="S_fullname">
                <span class="err"><i class="fa-solid fa-circle-exclamation"></i> Veuillez renseigner ce champ</span>
            </div>
            <div class="row mb-2">
                <label for="exampleFormControlInput4" class="form-label"> <span class="star">*</span>
                    Téléphone</label>
                <input type="text" class="form-control" id="exampleFormControlInput4" placeholder="06XXXXXXXX"
                    name="S_tel">
                <span class="err"><i class="fa-solid fa-circle-exclamation"></i> Veuillez renseigner ce champ</span>
            </div>
            <div class="row mb-2">
                <label for="exampleFormControlInput5" class="form-label"> <span class="star">*</span> Ville</label>
                <select class="form-select form-control" aria-label="Default select example" name="cities">
                    <option selected>Selectioner une ville</option>
                    <?php  $data = $conn->query("SELECT * FROM `ville`"); ?>
                    <?php  while($dt = $data->fetch(PDO::FETCH_ASSOC)){?>
                    <option value="<?php echo $dt['id_Ville']?>">
                        <?php echo $dt['villename'];?>
                    </option>
                    <?php }?>
                </select>
                <span class="err"><i class="fa-solid fa-circle-exclamation"></i> Veuillez renseigner ce champ</span>
            </div>
            <div class="row mb-2">
                <label for="exampleFormControlInput1" class="form-label"> <span class="star">*</span> E-mail
                    address</label>
                <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com"
                    name="S_email">
                <span class="err"><i class="fa-solid fa-circle-exclamation"></i> Veuillez renseigner ce champ</span>
            </div>

            <div class="row mb-2">
                <label for="exampleFormControlInput1" class="form-label"> <span class="star">*</span> Vérifier
                    e-mail</label>
                <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com"
                    name="S_re_email">
                <span class="err"><i class="fa-solid fa-circle-exclamation"></i> Veuillez renseigner ce champ</span>
            </div>

            <div class="row mb-2">
                <label for="exampleFormControlInput2" class="form-label"> <span class="star">*</span> Mot de
                    passe</label>
                <input type="password" class="form-control" id="exampleFormControlInput2" placeholder="password"
                    name="S_pass">
                <span class="err"><i class="fa-solid fa-circle-exclamation"></i> Veuillez renseigner ce champ</span>
                <p class="description mt-1">(Au moins 5 caractères) Choisissez un mot de passe qui n'est pas facile
                    à
                    deviner.</p>
            </div>

            <div class="row mb-3">
                <label for="exampleFormControlInput6" class="form-label"> <span class="star">*</span> Confirmer le
                    mot
                    de passe</label>
                <input type="password" class="form-control" id="exampleFormControlInput6" placeholder="password"
                    name="S_re_pass">
                <span class="err"><i class="fa-solid fa-circle-exclamation"></i> Veuillez renseigner ce champ</span>
            </div>
            <div class="row mb-2">
                <p class="faq">En cliquant sur "Créer un compte" j'accepte <a href="#"> la Politique de
                        confidentialité</a> de crowd Shoppe</p>
            </div>
            <div class="row mb-2">
                <button type="submit" id="btn_sign_up" class="btn btn-primary mt-2 p-2">Créer un compte <span
                        class="load loading"></span></button>
            </div>
        </form>
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
    $(document).ready(() => {



        const btns = document.querySelectorAll("button");
        btns.forEach((items) => {
            items.addEventListener("click", (evt) => {
                evt.target.classList.add("activeLoading");
            });
        });

        //the modal traitment
        var elements = $('.modal-overlay, .modal');

        $('.annonce').click(function() {
            elements.addClass('active');
        });

        $(".category").click(function() {
            let id = $(this).attr("data-cat-id");
            $(location).attr('href', 'annonce_traitment.php?id=' + id);
        })

        $('.annoncer').click(function() {
            elements.addClass('active');
        });

        $('.close-modal').click(function() {
            elements.removeClass('active');
        });
    })
    </script>
</body>

</html>
