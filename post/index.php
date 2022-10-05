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
            <div class="clearfix form-gp">
                <?php
                    if (!isset ($_GET['page']) ) {  
                        $page = 1;  
                    } else {  
                        $page = $_GET['page'];  
                    }  
                    $resultsPerPage = 3;  
                    $pageFirstResult = ($page-1) * $resultsPerPage;  
                    if(isset($_GET['search'])){
                        $title = $_GET['title'];
                        $query = "SELECT * FROM posts WHERE title LIKE '%".$title."%' ORDER BY id DESC";
                    }else{
                        $query = "SELECT * FROM posts ORDER BY id DESC";
                    }
                    $result = mysqli_query($conn, $query);  
                    $numberOfResult = mysqli_num_rows($result);  
    
                    $numberOfPage = ceil ($numberOfResult / $resultsPerPage);  
 
                    if(isset($_GET['search'])){
                        $title = $_GET['title'];
                        $query = "SELECT * FROM posts WHERE title LIKE '%".$title."%' ORDER BY id DESC LIMIT " . $pageFirstResult . ',' . $resultsPerPage;
                    }else{
                        $query = "SELECT * FROM posts ORDER BY id DESC LIMIT " . $pageFirstResult . ',' . $resultsPerPage;
                    }
                    $result = mysqli_query($conn, $query);    
                 
                ?>
                <div class="lft">
                <?php if(isset($_SESSION['user']['id'])){ ?>
                <button class="arrow arrow-btn border-radius"><a href="../post/create.php"><i class="fa-solid fa-plus"></i>Create</a></button>
                <?php } ?>
                </div>
                <div class="rgt">
                    <form action="" class="search-form">
                        <div class="">
                            <input type="text" class="search" name="title" placeholder="Search Title...">
                            <button class="btn search-btn border-radius" name="search"><i class="fa-solid fa-magnifying-glass"></i><span class="search-text">Search</span></button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="">
                <p class="success"><?php if(isset($_SESSION['success']['msg'])){ echo $_SESSION['success']['msg']; } ?></p>
                <table>
                    <thead>
                        <tr>
                            <th class="num">No</th>
                            <th class="uname">User Name</th>
                            <th class="title">Title</th>
                            <th class="image">Image</th>
                            <th class="cat-name">Category Name</th>
                            <th class="description">Description</th>
                            <th class="date">Date</th>
                            <th class="action">Action</th>
                        </tr>
                    </thead>
                    <?php if($numberOfResult < 1){
                    ?>
                    <tbody>
                        <tr>
                            <td colspan="8" style="text-align: center;"> There is no record</td>
                        </tr>
                    </tbody>
                    <?php }else{ ?>
                        <tbody>
                        <?php  
                        $getPage= isset($_GET['page']) ? $_GET['page'] : 1;  
                        $num = ($getPage - 1) * $resultsPerPage;
                        while($rows = mysqli_fetch_array($result)){
                            $id = $rows['id'];
                            echo "<tr>";

                            echo "<td>".++$num."</td>";

                            $sql = "SELECT * FROM users WHERE id={$rows['user_id']}";
                            $query = mysqli_query($conn,$sql);
                            $user = mysqli_fetch_assoc($query);

                            echo "<td>{$user['name']}</td>";
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
                                <li class="pill"><?php echo $row['name'] ?></li>
                            </ul>
                            <?php
                                }
                            }
                            echo "</td>";
                            echo "<td class='description'>{$rows['body']}</td>";
                            echo "<td class='date'>{$rows['updated_date']}</td>";
                            ?>
                            <td class='action'>
                            <a class='details border-radius' href='show.php?id=<?php echo $rows['id'] ?>'><i class='fa-solid fa-circle-info'></i>Details</a>
                            <?php if(isset($_SESSION['user']['id'])){ 
                                $userId = $_SESSION['user']['id'];
                                if($_SESSION['user']['name'] == $user['name']){
                            ?>
                            <a class='del border-radius' href='delete.php?id=<?php echo $rows['id'] ?>'><i class='fa-solid fa-trash'></i>Delete</a>
                            <a class='edit border-radius' href='edit.php?id=<?php echo $rows['id'] ?>'><i class='fa-solid fa-pen-to-square'></i>Edit</a>
                   
                            <?php } }  ?>
                            </td>
                        
                        <?php } 
                        echo "</tr>";
                        ?>
                    </tbody>
                    <?php } ?>
                </table>
            </div>
            <div class="pagination clearfix">
                <div class="paginate-up">
                    <?php 
                    if(isset($_GET['search'])){
                        $title = $_GET['title'];
                        $currentPage = isset($_GET['page'])?$_GET['page'] : 1;
                        for($page = 1; $page<= $numberOfPage; $page++) {  
                            $activeClass = "";
                            if($page==$currentPage)
                            {
                                $activeClass = "active-link";
                            }
                            echo '<a id="paginate" class="paginate border-radius '.$activeClass.'" href = "index.php?title='.$title.'&search=&page=' . $page . '">' . $page . ' </a>';  
                        }
                    }else{
                        $currentPage = isset($_GET['page'])?$_GET['page'] : 1;
                        for($page = 1; $page<= $numberOfPage; $page++) {  
                            $activeClass = "";
                            if($page==$currentPage)
                            {
                                $activeClass = "active-link";
                            }
                            echo '<a id="paginate" class="paginate border-radius '.$activeClass.'" href = "index.php?page=' . $page . '">' . $page . ' </a>';  
                        }
                    }
                    ?>
                </div>
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