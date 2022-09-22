<?php
    session_start();
    include 'db.php';
    include '../assets/php/functions.php';
   

if(isset($_POST['Lemail']) && isset($_POST['Lpass'])){
$email = $_POST['Lemail'];
$pass = $_POST['Lpass'];

if(empty($email)){
header("Location: ../loginpage.php?error=Email is required");
}
else if(empty($pass)){
header("Location: ../loginpage.php?error=Pass is Required");
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
$user_pass = $user['password'];
$user_infos = $user['id_infos'];
$role = $user['role'];
//var_dump($name);die;

//var_dump(getSingleValue($conn, "SELECT full_name FROM `users` u, `informations` i WHERE u.id = i.id AND i.id = " . $user_infos));die;
$name = getSingleValue($conn, "SELECT full_name FROM `users` u, `informations` i WHERE u.id_infos = i.id AND i.id = " . $user_infos);
//var_dump($name);die;
if($email === $user_email){
    if($pass === $user_pass){
        
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_id_infos'] = $user_infos;
        $_SESSION['user_email'] = $user_email;
        $_SESSION['role'] = $role;
        $_SESSION['name'] = $name;
        //var_dump($_SESSION);die;

    if($role == 'user'){
    header("Location: ../firstpage.php");}
    else{
    if($role == 'admin'){
    header("Location: ../dashboard/DashBoard.php");
    }
    }
}else{
header("Location: ../loginpage.php?error= email or password are wrong");
}
}else{
header("Location: ../loginpage.php?error= email or password are wrong");
}
}else{
header("Location: ../loginpage.php?error= email or password are wrong");
}
}
}else{
header("Location: ../loginpage.php");
exit();
}
