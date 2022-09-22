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
    <link rel="stylesheet" href="assets/css/login.css">
    <link href="https://fonts.googleapis.com/css?family=Medula+One" rel="stylesheet">


    <style>
    .connecter {
        display: none;
    }

    </style>
</head>

<body>
    <!--Nav Bar-->
    <?php include 'normalNavbar.php'?>

    <div class="container d-flex align-items-center flex-column">
        <form class="px-5 form-control border-0 pt-4" method="post" action="app/loginBack-end.php">
            <h2 class="display-5 text-center">Connexion</h2>
            <?php if(isset($_GET['error'])){?>
            <div class="error_msg alert alert-danger">
                <?php echo $_GET['error'];?>
            </div>
            <?php }?>
            <div class="row my-3">
                <label for="">E-mail</label><br>
                <input type="email" name="Lemail" placeholder="E-mail">
            </div>
            <div class="row my-3">
                <label for="">Mot de passe</label><br>
                <input type="password" name="Lpass" placeholder="Mot de passe">
            </div>
            <div class="row  my-3">
                <a href="reseatPass.php" class="ms-auto">Mot de passe oublie ?</a>
            </div>
            <div class="row  my-3">
                <button type="submit" class="btn btn-primary">SE CONNECTER</button>
            </div>

            <p class="text-center" style="font-family: 'Rubik', sans-serif;">Pas encore inscrit? <a href="signupage.php"
                    style="text-decoration: none; ">&nbsp;S'inscrire gratuitement.</a></p>
        </form>
        <div class="form-control px-5 mb-3 border-0 embedded">
            <div class="row mb-4">
                <div class="line1"></div>
            </div>
            <div class="row">
                <button type="submit" class="btn btn-primary d-flex justify-content-center"><i
                        class="fa-brands fa-facebook-f fs-5 "> </i>
                    &nbsp;&nbsp;&nbsp;CONTINUER
                    AVEC
                    FACEBOOK</button>
            </div>
        </div>
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
    $(document).ready(function() {

        })
        (function() {
            // removing the message 3 seconds after the page load
            setTimeout(function() {
                $('.error_msg').hide(300);
            }, 3000)
        })();

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
    </script>
</body>

</html>
