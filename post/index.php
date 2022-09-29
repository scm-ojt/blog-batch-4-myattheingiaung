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
    <title>Post List</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/post.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <?php include "../common/nav.php"; ?>
    <div class="">
        <div class="inner">
            <h2 class="cmn-ttl">Post List</h2>
            <div class="">
                <?php
                    if (!isset ($_GET['page']) ) {  
                        $page = 1;  
                    } else {  
                        $page = $_GET['page'];  
                    }  
                    $results_per_page = 2;  
                    $page_first_result = ($page-1) * $results_per_page;  
                    if(isset($_GET['search'])){
                        $title = $_GET['title'];
                        $query = "SELECT * FROM posts WHERE title LIKE '%".$title."%'";
                    }else{
                        $query = "SELECT * FROM posts";
                    }
                    $result = mysqli_query($conn, $query);  
                    $number_of_result = mysqli_num_rows($result);  
    
                    $number_of_page = ceil ($number_of_result / $results_per_page);  
 
                    if(isset($_GET['search'])){
                        $title = $_GET['title'];
                        $query = "SELECT * FROM posts WHERE title LIKE '%".$title."%' LIMIT " . $page_first_result . ',' . $results_per_page;
                    }else{
                        $query = "SELECT * FROM posts LIMIT " . $page_first_result . ',' . $results_per_page;
                    }
                    $result = mysqli_query($conn, $query);    
                 
                ?>
                <form action="">
                    <input type="text" class="search" name="title" placeholder="Title">
                    <button class="btn" name="search">Search</button>
                </form>
            </div>
            <div class="">
                <p class="success"><?php if(isset($_SESSION['success']['msg'])){ echo $_SESSION['success']['msg']; } ?></p>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Image</th>
                            <th>Category Name</th>
                            <th>Description</th>
                            <th>Created Date</th>
                            <th>Updated Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php   
                        while($rows = mysqli_fetch_array($result)){
                            $id = $rows['id'];
                            echo "<tr>";
                            echo "<td>{$rows['id']}</td>";
                            echo "<td>{$rows['title']}</td>";
                            echo "<td><img src='{$rows['image']}'></td>";

                            $sql = "SELECT categories.name,posts.id FROM categories 
                            JOIN category_post ON categories.id = category_post.category_id
                            JOIN posts ON posts.id = category_post.post_id
                            WHERE posts.id = '$id'";

                            $query = mysqli_query($conn,$sql);

                            echo "<td>";
                            while($row = mysqli_fetch_assoc($query)){
                                if($id = $row['id']){
                            ?>
                            <ul>
                                <li><?php echo $row['name'] ?></li>
                            </ul>
                            <?php
                                }
                            }
                            echo "</td>";
                            echo "<td>{$rows['body']}</td>";
                            echo "<td>{$rows['created_date']}</td>";
                            echo "<td>{$rows['updated_date']}</td>";
                            echo "<td><a class='del' href='destroy.php?id={$rows['id']}'><i class='fa-solid fa-trash'></i></a><a class='edit' href='edit.php?id={$rows['id']}'><i class='fa-solid fa-pen-to-square'></i></i></a></td>";
                        ?>
                        <?php } 
                        echo "</tr>";
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="pagination clearfix">
                <?php 
                for($page = 1; $page<= $number_of_page; $page++) {  
                    echo '<a class="paginate" href = "index.php?page=' . $page . '">' . $page . ' </a>';  
                } 
                ?>
            </div>
            </div>
        </div>
    </div>
    <?php 
    $_SESSION['error']=[];
    $_SESSION['success'] = [];
    ?>
</body>
</html>