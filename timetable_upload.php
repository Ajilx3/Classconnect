<?php
session_start();
include "config.php";
if(isset($_POST['upload'])){
  if(!isset($_SESSION['user_id'])){
    echo "please login to upload file";
    exit;

  }
  $uploaded_by=$_SESSION['user_id'];
  $filename=$_POST['filename'];
  $file_path=$_POST['filepath'];
  $sql="INSERT INTO timetable (filename,file_path,uploaded_by) VALUES('$filename','$file_path','$uploaded_by')";
  $result=mysqli_query($conn,$sql);
  if(!$result){
    mysqli_error($conn);

  }else{
    echo "created sucessfully";

  }
}

?>