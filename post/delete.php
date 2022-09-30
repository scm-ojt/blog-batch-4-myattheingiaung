<?php 
session_start();
require_once "../common/conn.php";

$id = $_GET['id'];

$post = "SELECT * FROM posts WHERE id=$id";
$query = mysqli_query($conn,$post);
$row = mysqli_fetch_assoc($query);
unlink($row['image']);

$sql = "DELETE FROM posts WHERE id=$id";

if(!mysqli_query($conn,$sql)){
    echo "Query Fail : ".mysqli_error($conn);
}else{
    $_SESSION['success']['msg'] = "Delete Successfully!";
    header("location:index.php");
}
 ?>