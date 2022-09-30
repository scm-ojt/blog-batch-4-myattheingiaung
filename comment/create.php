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
    <title>Create Comment</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/comment.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <?php 
        include "../common/nav.php";
        $errorMessage = 0;
        if(isset($_POST['commentAdd'])){
            if(empty($_POST['name'])){
                $errorMessage = 1;
                $_SESSION['error']['comment']= "comment enter";
            }
            if($errorMessage == 0){

                $sql = "INSERT INTO comments (pid,uid,body,created_date,updated_date) VALUES ('$name','$created_date','$updated_date')";
                if(mysqli_query($conn,$sql)){
                    header("location:index.php");
                }else{
                    echo "Query Fail : ".mysqli_error($conn);
                }
            }
        }
    ?>
    <div class="container">
        <div class="inner">
            <form action="" method="post" class="">
                <div class="form-group">
                    <input type="text" name="comment" placeholder="Add a comment"> 
                </div>
                <div class="btn-up">
                    <button name="commentAdd"><i class="fa-regular fa-paper-plane"></i></button>
                </div>
            </form>
            <small class="error"><?php if(isset($_SESSION['error']['comment'])){ echo $_SESSION['error']['comment']; } ?></small>
        </div>
    </div>
    <?php 
    $_SESSION['error']=[];
    $_SESSION['success'] = [];
    ?>
</body>
</html>