<?php
session_start();
include "config.php";

if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $row['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['first_name'] = $row['first_name'];
            $_SESSION['last_name'] = $row['last_name'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['college_id'] = $row['college_id']; // if you have it
            $_SESSION['class_code'] = $row['class_code']; // if you have it

            // Redirect based on role
            if ($row['role'] === 'admin') {
                header("Location: admindash.php"); // admin dashboard
                exit();
            } elseif ($row['role'] === 'teacher') {
                header("Location: teacherdash.php");
                exit();
            } else {
                header("Location: studentdash.php");
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

    $stmt->close();
}
$conn->close();
?>
