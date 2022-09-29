<?php 
session_start();
require_once "../common/conn.php";

$id = $_GET['id'];

$sql = "DELETE FROM posts WHERE id=$id";

if(!mysqli_query($conn,$sql)){
    echo "Query Fail : ".mysqli_error($conn);
}else{
    $_SESSION['success']['msg'] = "Delete Successfully!";
    header("location:index.php");
}
?>