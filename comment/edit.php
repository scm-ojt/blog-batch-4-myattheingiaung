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
    <link rel="stylesheet" href="../css/comments.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <?php 
        $errorMessage = 0;
        $id = $_GET['id'];
        $postId = $_SESSION['post']['id'];
        $userId = $_SESSION['user']['id'];
        $comment = "SELECT * FROM comments WHERE id=$id";
        $query = mysqli_query($conn,$comment);
        $row = mysqli_fetch_assoc($query);
        if(isset($_POST['commentEdit'])){
            if(empty($_POST['comment'])){
                $errorMessage = 1;
                $_SESSION['error']['comment']= "comment enter";
            }
            if($errorMessage == 0){
                $comment = $_POST['comment'];
                $dateTime = new DateTime("now", new DateTimeZone('Asia/Yangon')); 
                $updatedDate = $dateTime->format('Y.m.d  h:i:s');
                $sql = "UPDATE comments SET body='$comment',updated_date='$updatedDate' WHERE  id=$id";
                if(mysqli_query($conn,$sql)){
                    header("location:../post/show.php?id=$postId");
                }else{
                    echo "Query Fail : ".mysqli_error($conn);
                }
            }
        }
    ?>
    <div class="container">
        <div class="inner">
            <h2 class="ttl">Comment Edit</h2>
            <form action="" method="post" class="">
                <div class="form-group">
                    <input type="text" class="cmn-input" name="comment" placeholder="Update a comment" value="<?php echo $row['body']  ?>"> 
                </div>
                <div class="btn-up">
                    <button class="cmn-btn" name="commentEdit"><i class="fa-regular fa-paper-plane"></i></button>
                </div>
            </form>
            <small class="error"><?php if(isset($_SESSION['error']['comment'])){ echo $_SESSION['error']['comment']; } ?></small>
            <div class="clearfix">
                <button class="arrow"><a href='../post/show.php?id=<?php echo $postId ?>'><i class="fa-solid fa-arrow-left"></i>Back</a></button>
            </div>
        </div>
    </div>
    <?php 
    $_SESSION['error']=[];
    $_SESSION['success'] = [];
    ?>
</body>
</html>