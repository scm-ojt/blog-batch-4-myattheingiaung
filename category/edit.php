<?php 
session_start();
require_once"../common/conn.php"; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Update</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/categories.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <?php include "../common/nav.php"; ?>
    <div class="up clearfix">
    <div class="lft">
        <?php
        $errorMessage = 0;
        $id = $_GET['id'];
        $category = "SELECT * FROM categories WHERE id=$id";
        $query = mysqli_query($conn,$category);
        $row = mysqli_fetch_assoc($query);
        if(isset($_POST['categoryEdit'])){
            if(empty($_POST['name'])){
                $errorMessage = 1;
                $_SESSION['error']['name']= "Category name field is required!";
            }

            if($errorMessage == 0) {
                $name = $_POST['name'];
                $dateTime = new DateTime("now", new DateTimeZone('Asia/Yangon')); 
                $updatedDate = $dateTime ->format('Y.m.d h:i:s');
                $sql = "UPDATE categories SET name='$name',updated_date='$updatedDate' WHERE  id=$id";
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
                <form class="form" action="" method="post">
                    <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
                    <div class="form-group">
                        <label for="name">Category Name</label><br>
                        <input type="text" name="name" value="<?php echo $row['name'] ?>">
                        <small class="error"><?php if(isset($_SESSION['error']['name'])){ echo $_SESSION['error']['name']; } ?></small>
                    </div>
                    <div class="btn-up">
                        <button class="btn" name="categoryEdit"><i class='fa-solid fa-pen-to-square'></i>Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="rgt">
        <?php
        if (!isset ($_GET['page']) ) {  
            $page = 1;  
        } else {  
            $page = $_GET['page'];  
        }  
        $resultsPerPage = 10;  
        $pageFirstResult = ($page-1) * $resultsPerPage;  
        
        $sql = "SELECT * FROM categories ORDER BY id DESC";
        $result = mysqli_query($conn, $sql);  
        $numberOfResult = mysqli_num_rows($result);  

        $numberOfPage = ceil ($numberOfResult / $resultsPerPage);  
        $sql = "SELECT * FROM categories ORDER BY id DESC LIMIT " . $pageFirstResult . ',' . $resultsPerPage;

        $query = mysqli_query($conn, $sql);    
        ?>
        <div class="">
            <p class="success"><?php if(isset($_SESSION['success']['msg'])){ echo $_SESSION['success']['msg']; } ?></p>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Updated Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $getPage= isset($_GET['page']) ? $_GET['page'] : 1;  
                    $num = ($getPage - 1) * $resultsPerPage;
                    while($rows = mysqli_fetch_assoc($query)){
                        echo "<tr>";
                        echo "<td>".++$num."</td>";
                        echo "<td>{$rows['name']}</td>";
                        echo "<td>{$rows['updated_date']}</td>";
                        echo "<td><a class='del' href='destroy.php?id={$rows['id']}'><i class='fa-solid fa-trash'></i>Delete</a><a class='edit' href='edit.php?id={$rows['id']}'><i class='fa-solid fa-pen-to-square'></i>Edit</a></td>";
                    ?>
                    <?php } 
                    echo "</tr>";
                    ?>
                </tbody>
            </table>
        </div>
        <div class="pagination clearfix">
                <div class="paginate-up">
                    <?php 
                    $currentPage = isset($_GET['page'])?$_GET['page'] : 1;
                    for($page = 1; $page<= $numberOfPage; $page++) {  
                        $activeClass = "";
                        if($page==$currentPage)
                        {
                            $activeClass = "active-link";
                        }
                        echo '<a id="paginate" class="paginate border-radius '.$activeClass.'" href = "index.php?page=' . $page . '">' . $page . ' </a>';  
                    }
                    ?>
                </div>
            </div>
    </div>
    </div>
    
</body>
</html>