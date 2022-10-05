<?php
require_once "../common/conn.php";
session_start();
if(!isset($_SESSION['user']['id'])){
    if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {        
        header( 'HTTP/1.0 403 Forbidden', TRUE, 403 );
        die(header( 'location:index.php' ));
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Create</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/post.css"><link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <?php
        include "../common/nav.php";
        $errorMessage = 0;
            if(isset($_POST['postAdd'])){
                $fileType = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif','image/jfif'];
                if(empty($_FILES['image']['name'] )){
                    $errorMessage = 1;
                    $_SESSION['error']['name']= "Image field is required!";
                }elseif(!in_array($_FILES['image']['type'], $fileType)){
                    $errorMessage = 1;
                    $_SESSION['error']['type']= "Select .png, .jpeg, .gif file!";
                }
                if(empty($_POST['title'])){
                    $errorMessage = 1;
                    $_SESSION['error']['title']= "Title field is required!";
                }
                if(empty($_POST['cname'])){
                    $errorMessage = 1;
                    $_SESSION['error']['cname']= "Category name field is required!";
                }
                if(empty($_POST['description'])){
                    $errorMessage = 1;
                    $_SESSION['error']['description']= "Description field is required!";
                }

                if($errorMessage == 0) {
                    $targetDir = "../img/posts";
                    $fileExt = explode('.',$_FILES['image']['name']);
                    $fileActualExt = strtolower(end($fileExt));
                    $image =  $targetDir. "/".uniqid(rand(), true).".".$fileActualExt;
                    move_uploaded_file($_FILES['image']['tmp_name'], $image);
                    $title = $_POST['title'];
                    $description = $_POST['description'];
                    $dateTime = new DateTime("now", new DateTimeZone('Asia/Yangon')); 
                    $updatedDate = $dateTime->format('Y.m.d  h:i:s');
                    $createdDate = $dateTime->format('Y.m.d  h:i:s');
                    $userId = $_SESSION['user']['id'];
                    $sql = "INSERT INTO posts (image,title,body,created_date,updated_date,user_id) VALUES ('$image','$title','$description','$createdDate','$updatedDate','$userId')";
                    if(mysqli_query($conn,$sql)){
                        header("location:index.php");
                    }else{
                        echo "Query Fail : ".mysqli_error($conn);
                    }

                    if(isset($_POST["cname"])) {                       
                        foreach ($_POST['cname'] as $categoryId) { 
                            $lastPostId = "select last_insert_id()";
                            $query = mysqli_query($conn,$lastPostId);
                            $row = mysqli_fetch_assoc($query);
                            $postId = $row['last_insert_id()'];
                            $sql = "INSERT INTO category_post (post_id,category_id) VALUES ('$postId','$categoryId')";
                            if(!mysqli_query($conn,$sql)){
                                echo "Query Fail : ".mysqli_error($conn);
                            } 
                        }
                    }
                }
            }
    ?>
    <div class="container">
        <div class="up create-up">
        <div class="ttl-div clearfix">
            <h2 class="lft cmn-ttl">Create Post</h2>
            <button class="rgt border-radius"><a href="index.php"><i class="fa-solid fa-list"></i>Post List</a></button>
        </div>
        <form class="form" method="post" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="image">Image</label><br>
                <input type="file" name="image" value="value="<?php echo isset($_POST['image'])?$_POST['image']['name']:'' ?>">
                <small class="error"><?php if(isset($_SESSION['error']['name'])){ echo $_SESSION['error']['name']; } ?></small>
                <small class="error"><?php if(isset($_SESSION['error']['type'])){ echo $_SESSION['error']['type']; } ?></small>
            </div>
            <div class="form-group">
                <label for="title">Post Title</label><br>
                <input type="text" name="title">
                <small class="error"><?php if(isset($_SESSION['error']['title'])){ echo $_SESSION['error']['title']; } ?></small>
            </div>
            
            <div class="form-group">
                <label for="category-name">Category Name</label><br>
                <select name="cname[]" id="pre-selected-options" multiple>
                    <?php 
                        $categories = "SELECT * FROM categories";
                        $query = mysqli_query($conn,$categories);
                        while($rows = mysqli_fetch_assoc($query)){
                            echo "<option value='{$rows['id']}'>{$rows['name']}</option>";
                        }
                    ?>  
                </select>
                <small class="error"><?php if(isset($_SESSION['error']['cname'])){ echo $_SESSION['error']['cname']; } ?></small>
            </div>
            <div class="form-group">
                <label for="description">Description</label><br>
                <textarea name="description" id="" rows="5"></textarea>
                <small class="error"><?php if(isset($_SESSION['error']['description'])){ echo $_SESSION['error']['description']; } ?></small>
            </div>
            <div class="btn-up">
                <button class="btn full-btn create-btn border-radius" name="postAdd">Create</button>
            </div>
        </form>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#pre-selected-options').select2();
        });
    </script>
</body>
</html>