<?php
include "config.php";
session_start();
$sql = "SELECT f.*, u.name 
        FROM feedback f 
        LEFT JOIN users u ON f.user_id = u.id 
        ORDER BY f.submitted_at DESC";


$result =mysqli_query($conn,$sql);
echo "All feedbacks";
while($row=mysqli_fetch_assoc($result)){
    echo "<div style='border:1px solid #ccc; padding:15px; margin:10px 0'>
        <strong>From:</strong> $name <br>
        <strong>Category:</strong> {$row['category']}<br>
        <strong>Rating:</strong> {$row['rating']}⭐<br>
        <strong>Status:</strong> <span style='color:$statusColor;'>{$row['status']}</span><br>
        <p><strong>Feedback:</strong><br>{$row['feedback_text']}</p>
        
        <form action='reply_feedback.php' method='post'>
            <input type='hidden' name='id' value='{$row['id']}'>
            <textarea name='reply' rows='2' cols='40' placeholder='Write a reply...'>{$row['admin_reply']}</textarea><br>
            <button type='submit'>Update</button>
        </form>
    </div>";
}
?>
