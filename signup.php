<?php
session_start();
include "config.php";

if (isset($_POST['submit'])) {
    // 1. Collect & sanitize input
    $first_name   = trim($_POST['first_name']);
    $last_name    = trim($_POST['last_name']);
    $admission_no = trim($_POST['admission_no']);
    $email        = trim($_POST['email']);
    $register_no  = trim($_POST['register_no']);
    $dob          = $_POST['dob'];
    $course       = trim($_POST['course']);
    $college_id   = trim($_POST['college_id']); // Admin provided
    $password     = $_POST['password'] ?? '';

    // 2. Basic validation
    if (empty($first_name) || empty($admission_no) || empty($email) || empty($register_no) || empty($dob) || empty($course) || empty($college_id) || empty($password)) {
        die("All fields are required.");
    }

    // 3. Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    // 4. Password strength validation
    if (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}$/", $password)) {
        die("Password must be at least 6 characters long, with 1 uppercase, 1 lowercase, and 1 number.");
    }

    // 5. Check if college_id exists
    $stmt = $conn->prepare("SELECT id FROM colleges WHERE id = ?");
    $stmt->bind_param("i", $college_id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 0) {
        die("Invalid college ID. Please contact your admin.");
    }
    $stmt->close();

    // 6. Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 7. Insert user into DB (prepared statement)
    $stmt = $conn->prepare("INSERT INTO users 
        (first_name, last_name, admission_no, email, register_no, dob, course, password, college_id) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssi", 
        $first_name, 
        $last_name, 
        $admission_no, 
        $email, 
        $register_no, 
        $dob, 
        $course, 
        $hashed_password,
        $college_id
    );

    if ($stmt->execute()) {
        $_SESSION['temp_user_id'] = $conn->insert_id; // store temp user id for step 2
        header("Location: signup_step2.php"); // go to class_code page
        exit;
    } else {
        die("MySQL Error: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
}
?>
