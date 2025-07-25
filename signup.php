<?php
session_start();
include "config.php";

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $admission_no = $_POST['admission_no'];
    $email = $_POST['email'];
    $register_no = $_POST['register_no'];
    $dob = $_POST['dob'] ;
    $class = $_POST['class'] ;
    $course = $_POST['course'];
    $password = $_POST['password'];
    $password = $_POST['password'] ?? '';

    if (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}$/", $password)) {
        die("Invalid password format. Password must be at least 6 characters with 1 uppercase, 1 lowercase, and 1 number.");
    }

    $class_code = $_POST['class_code'] ;

    $valid = "CS2023@BVM";

    if ($class_code !== $valid) {
        die("Access denied");
    }

   

    $sql = "INSERT INTO users (name, admission_no, email, register_no, dob, class, course, password, class_code) 
            VALUES ('$name','$admission_no','$email','$register_no','$dob','$class','$course','$password','$class_code')";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("MySQL Error: " . mysqli_error($conn));
    } else {
        echo "Successfully created your account";
        header("Location: studentdash.html");
        exit;
    }
}
?>
