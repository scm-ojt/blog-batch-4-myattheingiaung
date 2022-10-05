<?php 
session_start();
require_once "../common/conn.php";

$id = $_GET['id'];
$postId = $_SESSION['post']['id'];

$sql = "DELETE FROM comments WHERE id=$id";

if(!mysqli_query($conn,$sql)){
    echo "Query Fail : ".mysqli_error($conn);
}else{
    $_SESSION['success']['msg'] = "Delete Successfully!";
    header("location:../post/show.php?id=$postId");
}
?>