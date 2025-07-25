<?php
session_start();
include "config.php"; 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $post_type = trim($_POST['post_type']);
    $message = trim($_POST['message']);
    $filename = null;

    // Handle file upload
    if (isset($_FILES['file']) && $_FILES['file']['error'] === 0) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // create dir if not exists
        }

        $originalName = basename($_FILES['file']['name']);
        $fileExtension = pathinfo($originalName, PATHINFO_EXTENSION);
        $newFilename = uniqid() . '.' . $fileExtension;
        $uploadPath = $uploadDir . $newFilename;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadPath)) {
            $filename = $newFilename;
        } else {
            die("❌ File upload failed!");
        }
    }

    $stmt = $conn->prepare("INSERT INTO classfeed (user_id, post_type, message, file_path) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $post_type, $message, $filename);
    
    if ($stmt->execute()) {
        header("Location:classfeed.php"); // redirect back with success
        exit();
    } else {
        die("❌ Database error: " . $stmt->error);
    }
} else {
    die("⚠️ Unauthorized access.");
}
?>
