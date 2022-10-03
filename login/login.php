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
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/login.css">
    <title>Login</title>
</head>
<body>
<?php
$errorMessage = 0;
if(isset($_POST['loginBtn'])){
    if(empty($_POST['email']) ){
        $errorMessage = 1;
        $_SESSION['error']['email']= "Email field is required!";
    }
    if(empty($_POST['password'])) {
        $errorMessage = 1;
        $_SESSION['error']['password']= "Password field is required!";
    }  
    $email = $_POST['email'];   
    if($errorMessage == 0) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $sql = "SELECT * FROM users where email='$email'";
        $query = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($query);
        $_SESSION['user']['id']= $row['id'];
        $_SESSION['user']['name']= $row['name'];
        if($row){
            if(password_verify($password,$row['password'])){
                header("location:../index.php");
            }else{
                $errorMessage = 1;
                $_SESSION['error']['one']= "Email or Password don't match";
            }
        }else{
            $errorMessage = 1;
            $_SESSION['error']['one']= "Email or Password don't match";
        }
    }   
}  
?>
    <section class="container">
        <div class="inner">
            <h2 class="cmn-ttl">Login Form</h2>
            <small class="error login-error"><?php if(isset($_SESSION['error']['one'])){ echo $_SESSION['error']['one']; } ?></small>
            <form class="form" action="" method="post">
                <div class="form-group">
                    <label for="email">Email</label><br>
                    <input type="email" name="email" value="<?php echo isset($email)?$email:'' ?>">
                    <small class="error"><?php if(isset($_SESSION['error']['email'])){ echo $_SESSION['error']['email']; } ?></small>
                </div>
                <div class="form-group">
                    <label for="password">Password</label><br>
                    <input type="password" name="password" value="<?php echo isset($password)?$password:'' ?>">
                    <small class="error"><?php if(isset($_SESSION['error']['password'])){ echo $_SESSION['error']['password']; } ?></small>
                </div>
                <div class="btn-up">
                    <button class="btn" name="loginBtn">Login</button>
                    <a class="link" href="../register/register.php">Register</a>&nbsp;|
                    <a class="link" href="../login/reset.php">Reset Password</a>
                </div>
            </form>
        </div>
    </section>

<?php 
$_SESSION['error']=[];
$_SESSION['error']['msg']=[];
 ?>
</body>
</html>