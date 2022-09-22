<?php
session_start();
include 'db.php';

if(isset($_POST['S_fullname']) && isset($_POST['S_tel']) 
  && isset($_POST['S_email']) && isset($_POST['S_re_email'])
  && isset($_POST['S_pass']) && isset($_POST['S_re_pass'])){

    function Validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    //get username from form
    $fullname = Validate($_POST['S_fullname']);
    $Tel = Validate($_POST['S_tel']);
    $email = validate($_POST['S_email']);
    $reemail = validate($_POST['S_re_email']);
    $pass  = validate($_POST['S_pass']);
    $repass  = validate($_POST['S_re_pass']);
    $city = filter_input(INPUT_POST, 'cities', FILTER_SANITIZE_STRING);
    
    if(empty($fullname)){
        header("Location: ../signupage.php?error=all data are required");
    }
    else if(empty($Tel)){
        header("Location: ../signupage.php?error=all data are required");
    }
    else if(empty($email)){
        header("Location: ../signupage.php?error=all data are required");
    }
    else if(empty($reemail)){
        header("Location: ../signupage.php?error=all data are required");
    }
    else if(empty($pass)){
        header("Location: ../signupage.php?error=all data are required");
    }
    else if(empty($repass)){
        header("Location: ../signupage.php?error=all data are required");
    }
    else if(empty($city)){
        header("Location: ../signupage.php?error=all data are required");
    }else{
        //First we search for tel taken or not

        //prepare the statement
        $stmt = $conn->prepare("SELECT * FROM `informations` WHERE telephone=?");
        //execute the statement
        $stmt->execute([$Tel]);
        //fetch result
        $user = $stmt->fetch();
        $user_fullname = $user['full_name'];
        if ($fullname === $user_fullname) {
            // tel already exists
            header("Location: ../signupage.php?error=Telephone deja kayn");
        } else {
            // tel does not exist
            //Search if the passwords are matched
            if($email === $reemail){
                if($pass === $repass){
                    if(strlen($pass) >= 6){
                    if(strlen($Tel) === 10){
                        try{
                            $conn->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);
                            
                            //first opertaion to informations
                            $sql = "INSERT INTO `informations` VALUES (NULL, '$fullname', '$Tel', $city)";
                            
                            $conn->exec($sql);
                            
                            //second one get the id of the user
                            $stmt = $conn->prepare("SELECT id FROM `informations` WHERE full_name = ? and id_Ville = ? and  telephone = ?");
                            
                            //execute the statement
                            $stmt->execute(array($fullname, $city, $Tel));
                            //fetch result
                            $user = null;
                            $user = $stmt->fetch();
                            $userInfos_id = $user['id'];
                            
                            //now im going the third one to insert data into `users` table      
                            
                            //third opertaion to informations
                            $sql1 = "INSERT INTO `users` VALUES (NULL, '$email', '$pass', $userInfos_id, 'default_user.png', 'user')";
                            $conn->exec($sql1);
                            
                            //fourth one get the id of the user
                            $stmt = $conn->prepare("SELECT id FROM `users` ORDER BY id DESC LIMIT 1");
                            
                            //execute the statement
                            $stmt->execute();
                            
                            //fetch result
                            $user = null;
                            $user = $stmt->fetch();
                            $userid = $user['id'];
                            
                            //set the data in global variables
                           
                            $_SESSION['user_id'] = $user_id;
                            $_SESSION['user_id_infos'] = $userInfos_id;
                            $_SESSION['user_email'] = $email;
                            $_SESSION['name'] = getSingleValue($conn, "SELECT full_name FROM `users` u, `informations` i WHERE u.id = i.id AND i.id = " . $userInfos_id);
                            
                            
                            header("Location: ../firstPage.php");

                        }
                        catch(PDOException $e){
                            echo $e->getMessage();
                        }
                    }else{
                        header("Location: ../signupage.php?error=telephone wrong");
                    }
                }else{
                    header("Location: ../signupage.php?error=please set a strong password");
                }
                }else{
                    header("Location: ../signupage.php?error=passwords are not the same");
                }
            }else{
                header("Location: ../signupage.php?error=emails re not the same");
            }
        }
    }
}else{
    header("Location: signup.php");
}
