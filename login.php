<?php
session_start();
include "config.php";

if (isset($_POST['go'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        
        // Get values from row
        $password = $row['password'];
        $role = $row['role'];

        // Password check (you can use password_verify if it's hashed)
        if ($password == $password) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['name'] = $row['first_name'];
            $_SESSION['name'] = $row['last_name'];
            $_SESSION['role'] = $role;

            echo "Welcome buddy";

            // Redirect based on role
            if ($role == 'admin') {
                header("Location: admindash.html"); // teacher is admin
                exit();
            } else {
                header("Location: studentdash.html");
                exit();
            }

        } else {
            echo "Wrong password!";
            header("refresh:2; url=login.html");
            exit();
        }
    } else {
        echo "Invalid email!";
        header("refresh:2; url=login.html");
        exit();
    }
}
?>


