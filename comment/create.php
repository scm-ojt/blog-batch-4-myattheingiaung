<?php 
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
        $errorMessage = 0;
        $postId = $_SESSION['post']['id'];
        if(isset($_SESSION['user']['id'])){
            $userId =$_SESSION['user']['id'];
        };
        if(isset($_POST['commentAdd'])){
            if(empty($_POST['comment'])){
                $errorMessage = 1;
                $_SESSION['error']['comment']= "comment enter";
            }
            if($errorMessage == 0){
                if(isset($userId)){
                    $comment = $_POST['comment'];
                    $dateTime = new DateTime("now", new DateTimeZone('Asia/Yangon')); 
                    $updatedDate = $dateTime->format('Y.m.d  h:i:s');
                    $createdDate = $dateTime->format('Y.m.d  h:i:s');
                    $sql = "INSERT INTO comments (post_id,user_id,body,created_date,updated_date) VALUES ('$postId','$userId','$comment','$createdDate','$updatedDate')";
                    if(mysqli_query($conn,$sql)){
                        header("location:../post/show.php?id=$postId");
                    }else{
                        echo "Query Fail : ".mysqli_error($conn);
                    }
                }else{
                    $_SESSION['error']['comment']= "Login First!";
                }
                
            }
        }
    ?>
    <div class="container">
        <div class="inner">
            <form action="" method="post" class="clearfix">
                <div class="form-group ps-fg">
                    <input type="text" class="cmn-input" name="comment" placeholder="Add a comment"> 
                </div>
                <div class="btn-up search-btn cmn-search-btn">
                    <button class="cmn-btn" name="commentAdd"><i class="fa-regular fa-paper-plane"></i></button>
                </div>
            </form>
            <small class="error"><?php if(isset($_SESSION['error']['comment'])){ echo $_SESSION['error']['comment']; } ?></small>
        </div>
    </div>
    <div class="cmn-show">
        <div class="inner">
            <ul>
                <?php
                print_r($result);
                $sql1 = "SELECT * FROM comments WHERE post_id=$postId";
                $qurey = mysqli_query($conn,$sql1);
                while($rows = mysqli_fetch_assoc($qurey)){
                    $user = "SELECT * FROM users JOIN comments ON users.id=comments.user_id WHERE comments.user_id={$rows['user_id']}";
                    $query = mysqli_query($conn,$user);
                    $result = mysqli_fetch_assoc($query);
                ?>
                    <p class="user-name"><i class="fa-solid fa-user"></i><?php echo $result['name'] ?></p>
                    <li class="clearfix list-group">
                        <p class="list-lft"><?php echo $rows['body'] ?></p>
                        <div class="list-rgt">
                            <?php if(isset($_SESSION['user']['id'])){ 
                                if($_SESSION['user']['name'] == $result['name']){
                            ?>
                                <p class="list-item"><a class='del radius' href='../comment/delete.php?id=<?php echo $rows['id'] ?>'><i class='fa-solid fa-trash'></i>Delete</a></p>
                                <p class="list-item"><a class='edit radius' href='../comment/edit.php?id=<?php echo $rows['id'] ?>'><i class='fa-solid fa-pen-to-square'></i>Edit</a></p>
                            <?php } } ?>
                        </div>
                    </li>
                <?php
                }
                ?>
            </ul>
        </div>
    </div>
    <?php 
    $_SESSION['error']=[];
    $_SESSION['success'] = [];
    ?>
</body>
</html>