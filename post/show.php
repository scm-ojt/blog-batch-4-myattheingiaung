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
    <title>Detail Posts</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/post.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <?php 
        include "../common/nav.php"; 
        $errorMessage = 0;
        $id = $_GET['id'];
        $_SESSION['post']['id'] = $id;
        $sql = "SELECT posts.*, categories.name FROM posts 
                JOIN category_post ON posts.id = category_post.post_id
                JOIN categories ON categories.id = category_post.category_id
                WHERE posts.id = '$id'";
        $query = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($query);
    ?>
    <div class="container">
        <div class="inner clearfix">
            <div class="card">
                <div class="card-body">
                    <?php 
                    $sql = "SELECT * FROM users WHERE id={$row['user_id']}";
                    $query = mysqli_query($conn,$sql);
                    $user = mysqli_fetch_assoc($query);
                    ?>
                    <p>
                        <span class="user-logo"><i class="fa-solid fa-user"></i></span>
                        <span class="user-name"><?php echo $user['name'] ?></span>
                    </p>
                    <h2 class="ttl"><?php echo $row['title'] ?></h2>
                    <?php
                    $sql = "SELECT categories.name,posts.id FROM categories 
                            JOIN category_post ON categories.id = category_post.category_id
                            JOIN posts ON posts.id = category_post.post_id
                            WHERE posts.id = '$id'";

                    $query = mysqli_query($conn,$sql);
                    echo "<p class='cname'>";
                    echo "<span class='cat-name'>Category Name - </span>";
                    while($result = mysqli_fetch_assoc($query)){
                        if($id = $result['id']){
                    ?>
                    <span class="cat-name"><?php echo $result['name']."," ?></span>
                    <?php 
                    }}
                    echo "</p>";
                    ?>
                    <div class="img-div">
                        <img class="show-img" src="<?php echo $row['image'] ?>" alt="">
                    </div>
                    <p class="description"><?php echo $row['body'] ?></p>
                </div>
                <button class="arrow"><a href="../post/index.php"><i class="fa-solid fa-arrow-left"></i>Back</a></button>
            </div>
            <div class="comment">
                <?php include '../comment/create.php' ?>
            </div>
        </div>
    </div>

    <?php 
    $_SESSION['error']=[];
    $_SESSION['success'] = [];
    ?>
</body>
</html>