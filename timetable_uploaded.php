<?php
include "config.php";
session_start();

$sql = "SELECT * FROM timetable ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Uploaded Timetables</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f9f9f9;
      padding: 20px;
    }

    .timetable-list {
      max-width: 600px;
      margin: auto;
      background: white;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .timetable-item {
      padding: 12px 0;
      border-bottom: 1px solid #eee;
    }

    .timetable-item:last-child {
      border-bottom: none;
    }

    .timetable-name {
      font-weight: bold;
      color: #333;
    }

    .timetable-link {
      color: #007bff;
      text-decoration: none;
      margin-right: 10px;
    }

    .timetable-link:hover {
      text-decoration: underline;
    }

    .meta {
      font-size: 0.9em;
      color: #777;
      margin-bottom: 5px;
    }

    .delete-form {
      display: inline;
    }

    .delete-button {
      background-color: transparent;
      border: none;
      color: #dc3545;
      cursor: pointer;
      font-size: 0.9em;
    }

    .delete-button:hover {
      text-decoration: underline;
    }
    .top-buttons {
  position: fixed;
  top: 20px;
  right: 20px;
  display: flex;
  gap: 10px;
  z-index: 999;
}

.back-button,
.home-button {
  background-color: #276cdb;
  color: #fff;
  padding: 10px 15px;
  font-size: 0.9em;
  border: none;
  border-radius: 6px;
  text-decoration: none;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.back-button:hover,
.home-button:hover {
  background-color: ;
}

.home-button {
  display: inline-block;
}

  </style>
</head>
<body>
    <div class="top-buttons">
  <button onclick="history.back()" class="back-button">🔙 Back</button>
  <a href="admindash.html" class="home-button">🏠 Home</a>
</div>

  <div class="timetable-list">
    <h2>📅 Uploaded Timetables</h2>

    <?php
    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='timetable-item'>";
        echo "<div class='timetable-name'>🗂️ " . htmlspecialchars($row['filename']) . "</div>";
        echo "<div class='meta'>👤 Uploaded by: " . htmlspecialchars($row['uploaded_by']) . "</div>";
        echo "<a class='timetable-link' href='" . htmlspecialchars($row['file_path']) . "' target='_blank'>📎 View File</a>";

        // 🔴 DELETE BUTTON FORM
        echo "<form class='delete-form' method='POST' action='timetable_delete.php' onsubmit='return confirm(\"Are you sure you want to delete this file?\")'>";
        echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
        echo "<input type='hidden' name='file_path' value='" . htmlspecialchars($row['file_path']) . "'>";
        echo "<button type='submit' class='delete-button'>🗑️ Delete</button>";

        echo "</form>";

        echo "</div>";
      }
    } else {
      echo "<p>No timetables uploaded yet.</p>";
    }
    ?>
  </div>
</body>
</html>
