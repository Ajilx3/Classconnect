<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['filename']);

    if (isset($_FILES['file_path']) && $_FILES['file_path']['error'] == 0) {
        $file = $_FILES['file_path'];
        $targetDir = "uploads/";
        $fileName = basename($file["title"]);
        $targetFilePath = $targetDir . time() . "_" . $fileName;

        $allowedTypes = ['pdf', 'doc', 'docx', 'jpg', 'png'];
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
                $stmt = $conn->prepare("INSERT INTO file (title, file_path) VALUES (?, ?)");
                $stmt->bind_param("ss", $title, $targetFilePath);
                if ($stmt->execute()) {
                    echo "✅ File uploaded successfully!";
                } else {
                    echo "❌ Database error: " . $conn->error;
                }
            } else {
                echo "⚠️ Error moving the uploaded file.";
            }
        } else {
            echo "❌ Invalid file type!";
        }
    } else {
        echo "⚠️ No file uploaded or upload error!";
    }
}
?>
