<?php
require_once "../common/conn.php";
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <?php
    $errorMessage = 0;
    if(isset($_POST['update'])){
        $email = $_POST['email'];
        $password = $_POST['new-pass'];
        $cpass = $_POST['cpass'];
        if(empty($email) || empty($password) || empty($cpass)){
            $errorMessage = 1;
            $_SESSION['error']['req-msg']= "Input field is required!";
        }
        $sql = "SELECT * FROM users where email='$email'";
        $query = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($query);
        if(!$row){
            $errorMessage = 1;
            $_SESSION['error']['user-msg']= "user ma hote buu register ayin lote yan";
            header("location:register.php");         
        }else{
            $id = $row['id'];       
            $newPassword = $_POST['new-pass']; 
            $confirmPassword = $_POST['cpass']; 

            $dt = new DateTime("now", new DateTimeZone('Asia/Yangon')); 
            $updated_date = $dt->format('Y.m.d , h:i:s');
        
            if($newPassword == $confirmPassword){
                $spass = password_hash($newPassword,PASSWORD_DEFAULT);                 
                $sql = "UPDATE users SET password='$spass',updated_date='$updated_date' WHERE  id=$id";
                if(mysqli_query($conn,$sql)){
                    $_SESSION['success']['message'] =  "Password Update Successfully!";
                    header("location:login.php");
                }else{
                    echo "Query Fail : ".mysqli_error($conn);
                }
            }else{
                echo "Password don't match";
            }
        } 
    }
    ?>
    <p class="success"><?php if(isset($_SESSION['success']['message'])){ echo $_SESSION['success']['message']; } ?></p>
    <p class="error"><?php if(isset($_SESSION['error']['user-msg'])){ echo $_SESSION['error']['user-msg']; } ?></p>
    <p class="error"><?php if(isset($_SESSION['error']['req-msg'])){ echo $_SESSION['error']['req-msg']; } ?></p>
    <section class="container">
        <div class="inner">
            <h2 class="cmn-ttl">Password Reset Form</h2>
            <form class="form" action="" method="post">
                <div class="form-group">
                    <label for="email">Email</label><br>
                    <input type="email" name="email">
                </div>
                <div class="form-group">
                    <label for="new-pass">New Password</label><br>
                    <input type="password" name="new-pass">
                </div>
                <div class="form-group">
                    <label for="cpass">Confirm Password</label><br>
                    <input type="password" name="cpass">
                </div>
                <div class="">
                    <button class="btn" name="update">Update</button>
                    <button class="btn"><a href="login.php">Login</a></button>
                </div>
            </form>
        </div>
    </section>
    <?php 
    $_SESSION['error']=[];
    $_SESSION['success'] = [];
    ?>
</body>
</html>