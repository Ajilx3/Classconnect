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

    if (isset($_FILES['file']) && $_FILES['file']['error'] === 0) {
        $fileTmp = $_FILES['file']['tmp_name'];
        $originalName = $_FILES['file']['name'];
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $uploadDir = "uploads/timetables/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); 
        }
        $newFileName = uniqid('tt_', true) . '.' . $extension;
        $filePath = $uploadDir . $newFileName;
        if (move_uploaded_file($fileTmp, $filePath)) {

            $sql = "INSERT INTO timetable (filename, file_path, uploaded_by) VALUES ('$filename', '$filePath', '$uploaded_by')";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                alert("timetable uploded sucessfully");
                header("location:timetable_uploaded.php");
            } else {
                echo " DB Error: " . mysqli_error($conn);
            }
        } else {
            echo " File move failed.";
        }
    } else {
        echo " No file uploaded or upload error.";
    }
}
?>
