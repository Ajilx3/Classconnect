<?php
session_start();
include "config.php";

// Make sure temp user id exists
if (!isset($_SESSION['temp_user_id'])) {
    die("Session expired. Please start signup again.");
}

$temp_user_id = $_SESSION['temp_user_id'];

// Fetch user's college_id from users table
$stmt = $conn->prepare("SELECT college_id FROM users WHERE id = ?");
$stmt->bind_param("i", $temp_user_id);
$stmt->execute();
$stmt->bind_result($college_id);
$stmt->fetch();
$stmt->close();

if (!$college_id) {
    die("User not found. Please start signup again.");
}

// Handle form submission
if (isset($_POST['submit'])) {
    $class_code = trim($_POST['class_code']);

    if (empty($class_code)) {
        die("Please enter the class code.");
    }

    // Verify class_code belongs to user's college
    $stmt = $conn->prepare("SELECT id FROM classes WHERE class_code = ? AND college_id = ?");
    $stmt->bind_param("si", $class_code, $college_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        die("Invalid class code for your college. Contact admin.");
    }
    $stmt->close();

    // Update user with class_code
    $stmt = $conn->prepare("UPDATE users SET class_code = ? WHERE id = ?");
    $stmt->bind_param("si", $class_code, $temp_user_id);

    if ($stmt->execute()) {
        // Signup complete, unset temp session
        unset($_SESSION['temp_user_id']);
        $_SESSION['user_id'] = $temp_user_id; // log in user
        header("Location: welcomeuser.php");
        exit;
    } else {
        die("MySQL Error: " . $stmt->error);
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - Step 2</title>
    <link rel="stylesheet" href="signup.css">
</head>
<body>
<header>
    <div class="navbar">
        <div class="logo"><h2>CLASSCONNECT</h2></div>
        <p>Enter your class to join your classroom</p>
    </div>
</header>
<main>
    <div class="signup-box">
        <h2>SIGNUP - Step 2</h2>
        <form action="" method="post" onsubmit="return validateForm();">
            <input type="text" name="class_code" id="class_code" placeholder="Enter your class code" required>
            <br><br>
            <input type="submit" name="submit" value="Join Class">
        </form>
    </div>
</main>
<script>
function validateForm() {
    const classCode = document.getElementById("class_code").value.trim();
    if (classCode === "") {
        alert("Please enter your class code.");
        return false;
    }
    return true;
}
</script>
</body>
</html>
