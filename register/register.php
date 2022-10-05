<?php 
session_start();
require_once "../common/conn.php"; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/login.css">
    <title>Register</title>
</head>
<body>
<?php
    $errorMessage = 0;
    if(isset($_POST['registerBtn'])){
        if(empty($_POST['name'])){
            $errorMessage = 1;
            $_SESSION['error']['name']= "Name field is required!";
        }
        if(empty($_POST['email'])){
            $errorMessage = 1;
            $_SESSION['error']['email']= "Email field is required!";
        }
        if(empty($_POST['password'])){
            $errorMessage = 1;
            $_SESSION['error']['password']= "Password field is required!";
        }
        if(empty($_POST['cpass'])){
            $errorMessage = 1;
            $_SESSION['error']['cpass']= "Confirm Password field is required!";
        }
        if ($_POST["password"] !== $_POST["cpass"]) {
            $errorMessage = 1;
            $_SESSION['error']['pass']= "Password do not match!";
        }
        $name = $_POST['name'];
        $email = $_POST['email'];
        if($errorMessage == 0) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $dateTime = new DateTime("now", new DateTimeZone('Asia/Yangon')); 
            $updatedDate = $dateTime->format('Y.m.d , h:i:s');
            $createdDate = $dateTime->format('Y.m.d , h:i:s');

            $sql = "INSERT INTO users (name,email,password,created_date,updated_date) VALUES ('$name','$email','$password','$createdDate','$updatedDate')";
            if(mysqli_query($conn,$sql)){
                header("location:../login/login.php");
            }else{
                echo "Query Fail : ".mysqli_error($conn);
            }
        }   
    }
?>
    <section class="container">
        <div class="inner">
            <h2 class="cmn-ttl">Registration Form</h2>
            <form class="form" action="" method="post">
                <div class="form-group">
                    <label for="name">Name</label><br>
                    <input type="text" name="name" value="<?php echo isset($name)?$name:'' ?>">
                    <small class="error"><?php if(isset($_SESSION['error']['name'])){ echo $_SESSION['error']['name']; } ?></small>
                </div>
                <div class="form-group"> 
                    <label for="email">Email</label><br>
                    <input type="email" name="email" value="<?php echo isset($email)?$email:'' ?>">
                    <small class="error"><?php if(isset($_SESSION['error']['email'])){ echo $_SESSION['error']['email']; } ?></small>
                </div>
                <div class="form-group">
                    <label for="password">Password</label><br>
                    <input type="password" name="password" value="<?php echo isset($password)?$password:'' ?>">
                    <small class="error"><?php if(isset($_SESSION['error']['password'])){ echo $_SESSION['error']['password']; } ?></small>
                    <small class="error"><?php if(isset($_SESSION['error']['pass'])){ echo $_SESSION['error']['pass']; } ?></small>
                </div>
                <div class="form-group">
                    <label for="cpass">Confirm Password</label><br>
                    <input type="password" onpaste="return false;" name="cpass" value="<?php echo isset($cpass)?$cpass:'' ?>">
                    <small class="error"><?php if(isset($_SESSION['error']['cpass'])){ echo $_SESSION['error']['cpass']; } ?></small>
                </div>
                <div class="btn-up">
                    <button class="btn" name="registerBtn">Register</button>
                    <button class="btn"><a href="../login/login.php">Login</a></button>
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