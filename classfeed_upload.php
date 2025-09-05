<?php
session_start();
include "config.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id'])) {
    die("⚠️ Unauthorized access.");
}

$user_id   = $_SESSION['user_id'];
$role      = $_SESSION['role'];
$post_type = trim($_POST['post_type'] ?? '');
$message   = trim($_POST['message'] ?? '');
$filename  = null;
$subject   = null;
$batch_id  = null;
$uploaded_by = null;

// 🚫 Prevent students from uploading
if ($role === 'student') {
    die("⚠️ Students cannot upload posts.");
}

// ✅ Get teacher allocation for this batch
$session_batch = $_SESSION['batch_id'] ?? 0; // teacher selected batch at login
$stmt = $conn->prepare("
    SELECT ta.subject, ta.batch_id, u.first_name, u.last_name
    FROM teacher_allocations ta
    JOIN users u ON ta.teacher_id = u.id
    WHERE u.id = ? AND ta.batch_id = ?
    LIMIT 1
");
$stmt->bind_param("ii", $user_id, $session_batch);
$stmt->execute();
$res = $stmt->get_result();

if ($row = $res->fetch_assoc()) {
    $subject    = $row['subject'] ?? 'General';
    $batch_id   = $row['batch_id'];
    $uploaded_by = trim(($row['first_name'] ?? '') . ' ' . ($row['last_name'] ?? ''));
} else {
    die("❌ Teacher allocation not found for this batch.");
}
$stmt->close();

// ✅ Handle file upload
if (!empty($_FILES['file']['name']) && $_FILES['file']['error'] === 0) {
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $originalName  = basename($_FILES['file']['name']);
    $fileExtension = pathinfo($originalName, PATHINFO_EXTENSION);
    $filename      = uniqid('', true) . '.' . $fileExtension;
    $uploadPath    = $uploadDir . $filename;

    if (!move_uploaded_file($_FILES['file']['tmp_name'], $uploadPath)) {
        die("❌ File upload failed!");
    }
}

// ✅ Insert post
$college_id = $_SESSION['college_id'];
$stmt = $conn->prepare("
    INSERT INTO classfeed (user_id, college_id, batch_id, post_type, message, file_path, subject)
    VALUES (?, ?, ?, ?, ?, ?, ?)
");
$stmt->bind_param("iiissss", $user_id, $college_id, $batch_id, $post_type, $message, $filename, $subject);

if (!$stmt->execute()) {
    die("❌ Database error: " . $stmt->error);
}

$post_id = $stmt->insert_id;
$stmt->close();

// ✅ Notification for students in the batch
$notif_message = "📢 {$uploaded_by} uploaded a new {$post_type} in {$subject}";
$notif_stmt = $conn->prepare("
    INSERT INTO notifications (message, uploaded_by, target_role, post_id, college_id, batch_id, is_read, created_at)
    VALUES (?, ?, 'student', ?, ?, ?, 0, NOW())
");
$notif_stmt->bind_param("ssiii", $notif_message, $uploaded_by, $post_id, $college_id, $batch_id);
$notif_stmt->execute();
$notif_stmt->close();

header("Location: classfeed.php");
exit;
?>
