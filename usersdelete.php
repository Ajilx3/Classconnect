<?php
include "config.php";
session_start();
if(isset($_GET['id'])){
$id=$_GET['id'];
$sql="DELETE  FROM users WHERE id='$id'";
$result=mysqli_query($conn,$sql);
if(!$result){
    mysqli_error($conn);

}else{
   echo "record deleted sucessfully";
   header("location:userslist.php");
}
}
?>