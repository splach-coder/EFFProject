<?php
    session_start();
    include 'db.php';
    include '../assets/php/functions.php';
   

    if(isset($_POST['Lemail'])){
        
        $email = $_POST['Lemail'];
    
        if(empty($email)){
            header("Location: ../reseatPass.php?error=Email is required");
        }
        else{
            #see if the user infos correct ot not
            $stmt = $conn->prepare("SELECT * FROM `users` WHERE email=?");
            $stmt->execute([$email]);
        
            if($stmt->rowCount() === 1){
            
                #get the table data
                $user = $stmt->fetch();
                
                $user_id = $user['id'];
                $user_email = $user['email'];
                
                
                if($email === $user_email){
                    header("Location: ../reseatPass2.php?id=".$user_id);
                }
                else{
                    header("Location: ../reseatPass.php?error=Email wrong");
                }
            }
            else{
                header("Location: ../reseatPass.php?error=Email wrong");
            }
        }
    }
