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
    <title>Edit Comment</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/comment.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <?php 
        $errorMessage = 0;
        $id = $_GET['id'];
        $comment = "SELECT * FROM comments WHERE id=$id";
        $query = mysqli_query($conn,$comment);
        $row = mysqli_fetch_assoc($query);
        if(isset($_POST['commentEdit'])){
            if(empty($_POST['comment'])){
                $errorMessage = 1;
                $_SESSION['error']['comment']= "comment enter";
            }
            if($errorMessage == 0){

                $pid = $_SESSION['post']['id'];
                $user_id = $_SESSION['user']['id'];
                $comment = $_POST['comment'];
                $dt = new DateTime("now", new DateTimeZone('Asia/Yangon')); 
                $updated_date = $dt->format('Y.m.d  h:i:s');
                $sql = "UPDATE comments SET body='$comment',updated_date='$updated_date' WHERE  id=$id";
                if(mysqli_query($conn,$sql)){
                    header("location:create.php");
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
                    <input type="text" name="comment" placeholder="Update a comment" value="<?php echo $row['body']  ?>"> 
                </div>
                <div class="btn-up">
                    <button name="commentEdit"><i class="fa-regular fa-paper-plane"></i></button>
                </div>
            </form>
            <small class="error"><?php if(isset($_SESSION['error']['comment'])){ echo $_SESSION['error']['comment']; } ?></small>
        </div>
    </div>
    <div class="cmn-show">
        <ul>
            <?php
            $sql1 = "SELECT * FROM comments";
            $qurey = mysqli_query($conn,$sql1);
            while($rows = mysqli_fetch_assoc($qurey)){
            ?>
                <li>
                    <p><?php echo $rows['body'] ?></p>
                    <p><a class='del' href='../comment/delete.php?id=<?php echo $rows['id'] ?>'><i class='fa-solid fa-trash'></i></a></p>
                    <p><a class='edit' href='../comment/edit.php?id=<?php echo $rows['id'] ?>'><i class='fa-solid fa-pen-to-square'></i></a></p>
                </li>
            <?php
            }
            ?>
        </ul>
    </div>
    <?php 
    $_SESSION['error']=[];
    $_SESSION['success'] = [];
    ?>
</body>
</html>