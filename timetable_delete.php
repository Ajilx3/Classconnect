<?php
include "config.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['file_path'])) {
    $id = intval($_POST['id']);
    $file_path = $_POST['file_path'];

    // Delete file from server
    if (file_exists($file_path)) {
        unlink($file_path); // delete file
    }

    // Delete from database
    $stmt = $conn->prepare("DELETE FROM timetable WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $stmt->close();
}

// Redirect back to timetable list
header("Location: timetable_uploaded.php"); // make sure this file name matches your view file
exit;
?>
