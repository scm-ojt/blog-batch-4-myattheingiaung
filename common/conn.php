<?php

$conn = mysqli_connect("127.0.0.1","root","1234","blog");
if(!$conn){
  die("connection fail : ".mysqli_connect_error());
}else{
  // sql to create table
$sql = "CREATE TABLE IF NOT EXISTS users (
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(45) NOT NULL,
  email VARCHAR(225) UNIQUE NOT NULL,
  password VARCHAR(225) NOT NULL,
  created_date DATETIME NOT NULL,
  updated_date DATETIME,
  PRIMARY KEY (id));";
  
$table =mysqli_query($conn, $sql) or die("bad create :$sql");
  if (!$table) {
    echo "Error creating table: " . mysqli_error($conn);
  } 
}

?>