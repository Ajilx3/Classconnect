<?php
session_start();
include "config.php"; // make sure this connects to your DB

if (isset($_POST['submit']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $message = trim($_POST['message']);
    $filename = "";

    // Handle optional file upload
    if (isset($_FILES['file']) && $_FILES['file']['name'] !== "") {
        $file = $_FILES['file'];
        $filename = time() . "_" . basename($file["name"]);
        $target = "uploads/" . $filename;

        if (!move_uploaded_file($file["tmp_name"], $target)) {
            echo "❌ File upload failed!";
            exit;
        }
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO notifications (message, file, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("ss", $message, $filename);

    if ($stmt->execute()) {
        header("Location: notifications.html"); // or wherever you want to redirect
        exit;
    } else {
        echo "❌ Failed to save notification!";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "⛔ Access Denied or invalid request!";
}
?>
