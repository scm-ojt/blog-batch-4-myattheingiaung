<?php
require_once "../common/conn.php";
session_start();
header("Access-Control-Allow-Origin:*");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/login.css">
    <title>Forgot Password</title>
</head>
<body>
    <?php  
    if(isset($_POST['send'])){
        $email = $_POST['email'];
        if(empty($email)){
            $_SESSION['error']['mes']= "Input field is required!";
        }
        require_once "vendor/autoload.php";
        require_once "vendor/PHPMailer/PHPMailer/src/PHPMailer.php";
        require_once "vendor/PHPMailer/PHPMailer/src/SMTP.php";
        require_once "vendor/PHPMailer/PHPMailer/src/Exception.php";
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        
        //smtp setting
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = "myattheingiaung1720@gmail.com";
        $mail->Password = 'chce pdkt kynd fmrw';
        $mail->Port = 25;
        $mail->SMTPSecure = "tls";

        //email setting
        $mail->setFrom('myattheingiaung1720@gmail.com','PHPMailer Example');
        $mail->addAddress($email,'Myat Theingi Aung');
        $mail->Subject = "Hello";
        $mail->Body = "<a href='http://localhost:8000/reset.php'>Reset Password Form </a>";
        $mail->AltBody = "This is the body in plain text for non-html mail clients";

        if(!$mail->send()){
            echo "Message could not be sent.";
            echo "Mailer Error : ". $mail->ErrorInfo;
        }else{
            $success = "Message has been sent successfully!";
        }
    }
    ?> 
    <p class="error"><?php if(isset($_SESSION['error']['message'])){ echo $_SESSION['error']['message']; } ?></p>
    <p class="mail-success"><?php if(!empty($success)){echo $success;} ?></p>
    <form class="login-form" action="" method="post">
        <div class="email-div">
            <label for="email">Email</label><br>
            <input type="email" name="email" placeholder="Enter your recovery email" required>
        </div>
        <div class="">
            <button class="" name="send">Send</button>
            <button class="login-link"><a href="login.php">Login</a></button>
        </div>
    </form>
    <?php 
    $_SESSION['error']=[];
    $_SESSION['success'] = [];
    ?>
</body>
</html>