<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Notification Center</title>
  <link rel="stylesheet" href="notification.css" />
</head>
<body>
  <div class="noti-container">
    <div class="noti-title">📢 Notifications</div>

    <!-- Only shows if the user is an admin -->
    <?php 
    include "config.php";
    session_start(); if ($_SESSION['role'] === 'admin') : ?>
      <button id="uploadBtn" onclick="togglePopup()">Upload</button>

      <!-- Hidden popup form for uploading -->
      <div id="popupForm" class="popup-form" style="display: none;">
        <form action="notificationback.php" method="POST" enctype="multipart/form-data">
          <textarea name="message" placeholder="Type your message..." required></textarea><br>
          <input type="file" name="file"><br><br>
          <button type="submit" name="submit">Submit</button>
        </form>
      </div>
    <?php endif; ?>

    <!-- This is where the notifications will be shown -->
    <div class="notification-list" id="notificationList">
      <?php 
      include "config.php";
      include "get_notification.php"; ?>
    </div>
  </div>

  <script>
    function togglePopup() {
      const popup = document.getElementById("popupForm");
      popup.style.display = (popup.style.display === "none") ? "block" : "none";
    }
  </script>
</body>
</html>
