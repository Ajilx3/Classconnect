<?php
session_start();
include "config.php";

if (isset($_POST['submit'])) {
    // 1️⃣ Collect & sanitize input
    $first_name   = trim($_POST['first_name']);
    $last_name    = trim($_POST['last_name']);
    $admission_no = trim($_POST['admission_no']);
    $email        = trim($_POST['email']);
    $register_no  = trim($_POST['register_no']);
    $dob          = $_POST['dob'];
    $course       = trim($_POST['course']);
    $college_code = trim($_POST['college_code']);
    $password     = $_POST['password'] ?? '';
    $class_code   = trim($_POST['class_code'] ?? '');

    // 2️⃣ Basic validation
    if (!$first_name || !$admission_no || !$email || !$register_no || !$dob || !$course || !$college_code || !$password) {
        die("⚠️ All fields are required.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("⚠️ Invalid email format.");
    }

    if (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}$/", $password)) {
        die("⚠️ Password must be at least 6 chars with 1 uppercase, 1 lowercase & 1 number.");
    }

    // 3️⃣ Fetch college_id using college_code
    $stmt = $conn->prepare("SELECT college_id FROM colleges WHERE college_code = ?");
    $stmt->bind_param("s", $college_code);
    $stmt->execute();
    $stmt->bind_result($college_id);
    $stmt->fetch();
    $stmt->close();

    if (!$college_id) {
        die("⚠️ Invalid college code. Contact admin.");
    }

    // 4️⃣ Fetch batch_id and department_id using class_code (optional)
    $batch_id = null;
    $department_id = null;

    if ($class_code) {
        $stmt = $conn->prepare("
            SELECT b.id AS batch_id, d.id AS department_id
            FROM batches b
            JOIN departments d ON b.department_id = d.id
            WHERE b.class_code = ? AND d.college_id = ?
        ");
        $stmt->bind_param("si", $class_code, $college_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($batch_id, $department_id);
        $stmt->fetch();
        $stmt->close();

        if (!$batch_id) {
            die("❌ Invalid class code for your college. Contact admin.");
        }
    }

    // 5️⃣ Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 6️⃣ Insert user
    $stmt = $conn->prepare("
        INSERT INTO users 
        (first_name, last_name, admission_no, email, register_no, dob, course, password, college_code, college_id, batch_id, department_id, role)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'student')
    ");
    $stmt->bind_param(
        "ssssssssiiii",
        $first_name, $last_name, $admission_no, $email, $register_no, $dob, $course,
        $hashed_password, $college_code, $college_id, $batch_id, $department_id
    );

    if ($stmt->execute()) {
        $_SESSION['temp_user_id'] = $conn->insert_id; // Save temp session
        session_write_close();
        header("Location: signup_step2.php");
        exit;
    } else {
        die("❌ MySQL Error: " . $stmt->error);
    }
}
?>

