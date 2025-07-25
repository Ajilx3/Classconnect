<?php
include "config.php";
if(isset($_POST['submit'])){
    $question=$_POST['quesion'];
    $option=$_POST['option'];
    $sql="INSERT INTO poll (questions) VALUES('$question')";
    $result=mysqli_query($conn,$sql);
    $poll_id=mysqli_insert_id($conn);
}