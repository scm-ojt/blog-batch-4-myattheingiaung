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
    <title>Post Edit</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/post.css">
    <link rel="stylesheet" href="../css/post.css"><link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <?php
        include "../common/nav.php";
        $errorMessage = 0;
        $id = $_GET['id'];
        $_SESSION['pid'] = $id;
        $sql = "SELECT posts.*, categories.name FROM posts 
                JOIN category_post ON posts.id = category_post.post_id
                JOIN categories ON categories.id = category_post.category_id
                WHERE posts.id = '$id'";
        $query = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($query);
            if(isset($_POST['postEdit'])){
                $fileType = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                if($_FILES['image']['name'] != ''){
                    if(!in_array($_FILES['image']['type'], $fileType)){
                        $errorMessage = 1;
                        $_SESSION['error']['type']= "Select .png, .jpeg, .gif file!";
                    }elseif($errorMessage == 0){
                        unlink($row['image']);
                        $targetDir = "../img/posts";
                        $fileExt = explode('.',$_FILES['image']['name']);
                        $fileActualExt = strtolower(end($fileExt));
                        $image =  $targetDir. "/".uniqid(rand(), true).".".$fileActualExt;
                        move_uploaded_file($_FILES['image']['tmp_name'], $image);
                    }
                }else{
                    $image = $row['image'];
                }
                if(empty($_POST['title'])){
                    $errorMessage = 1;
                    $_SESSION['error']['title']= "Title field is required!";
                }
                if(empty($_POST['description'])){
                    $errorMessage = 1;
                    $_SESSION['error']['description']= "Description field is required!";
                }

                if($errorMessage == 0) {
                    $title = $_POST['title'];
                    $description = $_POST['description'];
                    $dateTime = new DateTime("now", new DateTimeZone('Asia/Yangon')); 
                    $updatedDate = $dateTime->format('Y.m.d h:i:s');
                    $sql = "UPDATE posts SET image='$image',title='$title',body='$description',updated_date='$updatedDate' WHERE  id=$id";
                    if(mysqli_query($conn,$sql)){
                        header("location:index.php");
                    }else{
                        echo "Query Fail : ".mysqli_error($conn);
                    }

                    if(isset($_POST["cname"])) {
                        $sql = "DELETE FROM category_post WHERE post_id=$id";
                        if(!mysqli_query($conn,$sql)){
                            echo "Query Fail : ".mysqli_error($conn);
                        }    
                        foreach ($_POST['cname'] as $cid) {
                            $sql = "INSERT INTO category_post (post_id,category_id) VALUES ('$id','$cid')";
                            if(!mysqli_query($conn,$sql)){
                                echo "Query Fail : ".mysqli_error($conn);
                            }
                        }
                    }
                }
            }
    ?>
    <div class="up create-up">
        <div class="ttl-div clearfix">
            <h2 class="lft cmn-ttl">Edit Post</h2>
            <button class="rgt border-radius"><a href="index.php"><i class="fa-solid fa-list"></i>Post List</a></button>
        </div>
        <form class="form" method="post" action="" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
            <div class="form-group">
                <label for="image">Image</label><br>
                <?php 
                    if($row['image']){
                        echo "<img src='{$row['image']}'>";
                    }
                ?>
                <input type="file" name="image" value="<?php echo $row['image']  ?>">
                <small class="error"><?php if(isset($_SESSION['error']['type'])){ echo $_SESSION['error']['type']; } ?></small>
            </div>
            <div class="form-group">
                <label for="title">Post Title</label><br>
                <input type="text" name="title" value="<?php echo $row['title'] ?>">
                <small class="error"><?php if(isset($_SESSION['error']['title'])){ echo $_SESSION['error']['title']; } ?></small>
            </div>
            <div class="form-group">
                <label for="category-name">Category Name</label><br>
                <select name="cname[]" id="pre-selected-options" multiple>
                    <?php 
                        $sql = "SELECT category_post.category_id,categories.name,posts.id FROM categories 
                        JOIN category_post ON categories.id = category_post.category_id
                        JOIN posts ON posts.id = category_post.post_id
                        WHERE posts.id = '$id'";
                        $query = mysqli_query($conn,$sql);
                        while($result = mysqli_fetch_array($query)){
                            $catId[] = $result['category_id'];
                        }; 
                        $category = "SELECT * FROM categories";
                        $query = mysqli_query($conn,$category);
                        while($rows = mysqli_fetch_assoc($query)){
                        ?>
                            
                            <option value="<?php echo $rows['id'] ?>" <?php echo in_array($rows['id'],$catId)?"selected":"" ?> ><?php echo $rows['name'] ?></option> 
                    <?php
                            }
                     ?>  
                </select>
                <?php
                ?>
                <small class="error"><?php if(isset($_SESSION['error']['cname'])){ echo $_SESSION['error']['cname']; } ?></small>
            </div>
            <div class="form-group">
                <label for="description">Description</label><br>
                <textarea name="description" id="" cols="30" rows="5"><?php echo $row['body'] ?></textarea>
                <small class="error"><?php if(isset($_SESSION['error']['description'])){ echo $_SESSION['error']['description']; } ?></small>
            </div>
            <div class="btn-up">
                <button class="btn full-btn border-radius" name="postEdit">Update</button>
            </div>
        </form>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#pre-selected-options').select2();
        });
    </script>
</body>
</html>