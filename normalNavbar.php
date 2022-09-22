<?php
if(isset($_SESSION['user_id'])){
    $nbr = getSingleValue($conn, "SELECT count(*) FROM `msgfromadmin` WHERE seen = 0 AND to_user = " . $_SESSION['user_id']);}
?>
<nav class="main-nav navbar navbar-expand-lg navbar-light bg-light" style="-webkit-box-shadow: 11.5px 11px 16.5px -05px #ddd;
-moz-box-shadow: 11.5px 11px 16.5px -0.5px #ddd;
box-shadow: 11.5px 11px 16.5px -0.5px #ddd; ">
    <div class="container-fluid">
        <a class="navbar-brand" href="firstPage.php"><span class="brand-name">crowd</span><span
                class="brand-name">Shoppe</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll"
            aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="nvlinks collapse navbar-collapse" id="navbarScroll">
            <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                <div class="nav-btns d-flex">
                    <?php                      
                        if(isset($_SESSION['user_id'])  &&  isset($_SESSION['user_id_infos'])  &&  isset($_SESSION['user_email'])){
                    ?>
                    <div class="select-menuu">
                        <div class="select-btnn">
                            <div class="imgBx">
                                <img src="assets/images/default_user.png" alt="">
                            </div>
                            <span class="sBtn-text">
                                <?=$_SESSION['name']?>
                            </span>
                            <span id="nbrmsgs"
                                style="width: 13px;height: 13px;background-color: red;border-radius: 50%;color: white;font-size: 12px;display: flex;justify-content: center;align-items: center; font-weight: 600;position: relative;top: -5px;left: 20px;">
                                <?php if($nbr != 0){ echo $nbr;}else{?>
                                <script>
                                $("#nbrmsgs").css("display", "none")
                                </script>
                                <?php }?>
                            </span>
                            <i class="fa-solid fa-bell"></i>
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                        <ul class="options">
                            <li class="option">
                                <i class="fas fa-user" style="color: #171515;"></i>
                                <a href="account.php" style="text-decoration:
                                none;" class="option-text">Profile</a>
                            </li>
                            <li class="option">
                                <i class="fas fa-message" style="color: #E1306C;"></i>
                                <a href="chat-part.php" style=" text-decoration: none;" class="option-text">Messages</a>
                            </li>
                            <li class="option">
                                <i class="fa-solid fa-table" style="color: #0E76A8;"></i>
                                <a href="account.php" style="text-decoration:
                                none;" class="option-text">Mes Announces</a>
                            </li>
                            <li class="option">
                                <i class="fa-solid fa-arrow-right-from-bracket" style="color:
                                #4267B2;"></i>
                                <a href="app/logout.php" style="text-decoration:
                                none;" class="option-text">Log out</a>
                            </li>
                        </ul>
                        <div class="wrapper d-none 3216">
                            <div class="tab_wrap">
                                <ul>
                                    <li id="all" data-li="all" class="active">All</li>
                                    <li id="unread" data-li="unread">Unread</li>
                                </ul>
                            </div>

                            <div class="inbox">
                                <ul id="con">
                                    <?php $msgs = 'SELECT id, msg, DATE_FORMAT(created_at, "%H:%i") as tm, seen, link FROM `msgfromadmin` WHERE to_user = '.$_SESSION['user_id'].' ORDER BY id DESC';
                                    $data = $conn->query($msgs);
                                    while($dt = $data->fetch(PDO::FETCH_ASSOC)){?>
                                    <li>
                                        <div class="all">
                                            <div class="li_left">
                                                <img src="assets/icons/logo.png" alt="">
                                            </div>
                                            <div class="li_right" data-id="<?php echo $dt['id']?>">
                                                <div class="message" data-link="<?=$dt['link']?>">
                                                    <div class="title" style="font-size: 18px; font-weight: 400;">crowd
                                                        shopee</div>
                                                    <div class="sub_title">
                                                        <?php echo $dt['msg']?>
                                                    </div>
                                                </div>
                                                <div class="time_status">
                                                    <div class="time"><?php echo $dt['tm']?></div>
                                                    <?php if($dt['seen'] == 0){?>
                                                    <div class="status">
                                                        <i class="fas fa-envelope"></i>
                                                    </div>
                                                    <?php }?>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <?php }?>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <a href="#" class="btn annoncer"><i class="fas fa-plus"></i>
                        <p>Annoncer</p>
                    </a>

                    <!---->
                    <!--div class="navigation">
                        <div class="userBx">
                            <div class="imgBx">
                                <img src="assets\images\default_user.png" alt="">
                            </div>
                            <p class="username">Anas ben</p>
                        </div>
                        <div class="menuToggle"></div>
                        <ul class="menu">
                            <li><a href="account.php">
                                    <ion-icon name="person-outline"></ion-icon>Profile
                                </a></li>
                            <li><a href="chat-part.php">
                                    <ion-icon name="chatbox-outline"></ion-icon>Messages
                                </a></li>
                            <li><a href="#">
                                    <ion-icon name="settings-outline"></ion-icon>Settings
                                </a></li>
                            <li><a href="app/logout.php">
                                    <ion-icon name="log-out-outline"></ion-icon>Logout
                                </a></li>
                        </ul>
                        </div-->
                    <?php 
                    }else{?>
                    <a href="loginpage.php" class="nav-item btn connecter">Se Connecter</a>
                    <a href="#" class="nav-item btn annonce"><i class="fas fa-plus"></i> Annoncer</a>
                    <?php
                        }
                    ?>

                </div>
            </ul>

        </div>
    </div>
</nav>
