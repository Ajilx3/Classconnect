<?php
include "config.php";

// Get all notifications sorted by newest first
$sql = "SELECT * FROM notifications ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="notification">';
        echo '<p class="message">' . htmlspecialchars($row["message"]) . '</p>';
        echo '<p class="date">' . date("d M Y, h:i A", strtotime($row["created_at"])) . '</p>';

        if (!empty($row["file"])) {
            echo '<a href="uploads/' . $row["file"] . '" target="_blank" download>📄 Download Attachment</a>';
        }

        echo '</div>';
    }
} else {
    echo '<p class="no-notifications">No notifications found.</p>';
}

$conn->close();
?>
