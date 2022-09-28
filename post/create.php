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
    <title>Post Create</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/post.css">
</head>
<body>
    <?php
        include "../common/nav.php";
        $errorMessage = 0;
            if(isset($_POST['postAdd'])){
                $fileType = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
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
                    $target_dir = "../img/posts";
                    $fileExt = explode('.',$_FILES['image']['name']);
                    $fileActualExt = strtolower(end($fileExt));
                    $image =  $target_dir. "/".uniqid(rand(), true).".".$fileActualExt;
                    move_uploaded_file($_FILES['image']['tmp_name'], $image);
                    $title = $_POST['title'];
                    $description = $_POST['description'];
                    $dt = new DateTime("now", new DateTimeZone('Asia/Yangon')); 
                    $updated_date = $dt->format('Y.m.d , h:i:s');
                    $created_date = $dt->format('Y.m.d , h:i:s');
                    $sql = "INSERT INTO posts (image,title,body,created_date,updated_date) VALUES ('$image','$title','$description','$created_date','$updated_date')";
                    if(mysqli_query($conn,$sql)){
                        // header("location:create.php");
                        echo "<p>success</p>";
                    }else{
                        echo "Query Fail : ".mysqli_error($conn);
                    }

                    if(isset($_POST["cname"])) { 
                        foreach ($_POST['cname'] as $cid) {
                            $id = 1;
                            echo "Selected Category Id - $cid <br>";
                            $sql = "INSERT INTO category_post (post_id,category_id) VALUES ('$id','$cid')";
                            if(mysqli_query($conn,$sql)){
                                // header("location:create.php");
                                echo "<p>category_post success</p>";
                            }else{
                                echo "Query Fail : ".mysqli_error($conn);
                            }
                        }
                    }
                }
            }
    ?>
    <div class="up">
        <h2>Create Post</h2>
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
                <select name="cname[]" id="" multiple>
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
                <textarea name="description" id="" cols="30" rows="5"></textarea>
                <small class="error"><?php if(isset($_SESSION['error']['description'])){ echo $_SESSION['error']['description']; } ?></small>
            </div>
            <div class="btn-up">
                <button class="btn" name="postAdd">Add</button>
            </div>
        </form>
    </div>
</body>
</html>