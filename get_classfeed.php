<?php
include "config.php";
session_start();

$posts = [];

$sql = "SELECT c.*, u.name, u.role 
        FROM classfeed c 
        JOIN users u ON c.user_id = u.id 
        ORDER BY c.created_at DESC";

$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    $posts[] = $row;
}

header('Content-Type: application/json');
echo json_encode($posts);
?>
