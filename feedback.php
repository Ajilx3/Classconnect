<?php
include "config.php";
session_start();

$feedback_text = trim($_POST['feedback_text'] ?? '');
$category = trim($_POST['category'] ?? '');
$rating = trim($_POST['rating'] ?? '');
$is_anonym = isset($_POST['anonymous']) ? 1 : 0;

$user_id = !$is_anonym && isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'NULL';

if (!empty($feedback_text)) {
    $sql = "INSERT INTO feedback (user_id, feedback_text, category, rating, is_anonymous) 
            VALUES ($user_id, '$feedback_text', '$category', $rating, $is_anonym)";
    
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "✅ Feedback submitted successfully!";
        header ("location:admindash.html?submitted=true");
        exit();
    } else {
        echo "❌ Error: " . mysqli_error($conn);

    }
} else {
    echo "⚠️ Please enter feedback text.";
}
?>
