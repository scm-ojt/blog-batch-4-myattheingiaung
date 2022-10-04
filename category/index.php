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
    <title>Category Create & List</title>
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
            if(isset($_POST['categoryAdd'])){
                if(empty($_POST['name'])){
                    $errorMessage = 1;
                    $_SESSION['error']['name']= "Category name field is required!";
                }
                $name = $_POST['name'];
                if($errorMessage == 0) {
                    $name = $_POST['name'];
                    $dt = new DateTime("now", new DateTimeZone('Asia/Yangon')); 
                    $updated_date = $dt->format('Y.m.d h:i:s');
                    $created_date = $dt->format('Y.m.d h:i:s');
                    $sql = "INSERT INTO categories (name,created_date,updated_date) VALUES ('$name','$created_date','$updated_date')";
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
                    <div class="form-group">
                        <label for="name">Category Name</label><br>
                        <input type="text" name="name" placeholder="Enter Category Name..." value="<?php echo isset($name)?$name:'' ?>">
                        <small class="error"><?php if(isset($_SESSION['error']['name'])){ echo $_SESSION['error']['name']; } ?></small>
                        </div>
                        <div class="btn-up">
                            <button class="btn" name="categoryAdd">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="rgt">
            <?php
            $sql1 = "SELECT * FROM categories ORDER BY id DESC";
            $qurey = mysqli_query($conn,$sql1);
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
                        $i = 0;
                        while($rows = mysqli_fetch_assoc($qurey)){
                            echo "<tr>";
                            echo "<td>".++$i."</td>";
                            echo "<td>{$rows['name']}</td>";
                            echo "<td>{$rows['updated_date']}</td>";
                            echo "<td><a class='del' href='delete.php?id={$rows['id']}'><i class='fa-solid fa-trash'></i></a><a class='edit' href='edit.php?id={$rows['id']}'><i class='fa-solid fa-pen-to-square'></i></i></a></td>";
                        ?>
                        <?php } 
                        echo "</tr>";
                        ?>
                    </tbody>
                </table>
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