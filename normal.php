<?php
session_start();

// Dummy data for demo. Replace this with your actual session/database logic.
$_SESSION['user'] = [
    'name' => '$name',
    'admission_no' => '$admission_no',
    'register_no' => '$register_no',
    'email' => '$email',
    'role' => 'Student',
    'dob' => '$dob',
    'class' => '$class',
    'course' => '$course'
];

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ClassConnect - Dashboard</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      box-sizing: border-box;
    }

    body {
      background: #f4f4f4;
    }

    .navbar {
      background: #075e54;
      color: white;
      padding: 15px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .logo {
      font-size: 20px;
      font-weight: bold;
    }

    .nav-buttons button {
      background: #25d366;
      border: none;
      color: white;
      padding: 8px 12px;
      margin-left: 10px;
      border-radius: 5px;
      cursor: pointer;
    }

    .main-content {
      padding: 30px;
      text-align: center;
    }

    .button-grid button {
      margin: 10px;
      padding: 15px 20px;
      font-size: 16px;
      border: none;
      border-radius: 8px;
      background: #ededed;
      cursor: pointer;
      transition: 0.3s;
    }

    .button-grid button:hover {
      background: #d4f3d1;
    }

    .profile-popup {
      position: fixed;
      top: 0;
      right: -400px;
      width: 350px;
      height: 100vh;
      background: white;
      box-shadow: -2px 0 8px rgba(0,0,0,0.2);
      padding: 20px;
      transition: right 0.3s ease;
      z-index: 999;
    }

    .profile-popup.active {
      right: 0;
    }

    .profile-popup h3 {
      margin-bottom: 20px;
      border-bottom: 1px solid #ccc;
      padding-bottom: 10px;
    }

    .profile-popup p {
      margin: 10px 0;
    }

    .close-btn {
      background: crimson;
      color: white;
      border: none;
      padding: 5px 10px;
      cursor: pointer;
      float: right;
    }
  </style>
</head>
<body>

<header class="navbar">
  <div class="logo">ClassConnect</div>
  <div class="nav-buttons">
    <button onclick="toggleProfile()">👤 Profile</button>
    <button>🌙</button>
  </div>
</header>

<div class="main-content">
  <h1>Welcome, <?php echo htmlspecialchars($user['name']); ?> 👋</h1>
  <p>Your all-in-one student dashboard</p>
  <div class="button-grid">
    <button>📝 Class Feed</button>
    <button>📁 Files</button>
    <button>💬 Feedback</button>
    <button>📅 Timetable</button>
    <button>📈 Results</button>
    <button>📊 Polls</button>
    <button>🔔 Notifications</button>
  </div>
</div>

<!-- Profile Popup -->
<div id="profilePopup" class="profile-popup">
  <button class="close-btn" onclick="toggleProfile()">Close</button>
  <h3>👤 Profile Details</h3>
  <p><strong>Name:</strong> <?php echo $user['name']; ?></p>
  <p><strong>Admission No:</strong> <?php echo $user['admission_no']; ?></p>
  <p><strong>Register No:</strong> <?php echo $user['register_no']; ?></p>
  <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
  <p><strong>Role:</strong> <?php echo $user['role']; ?></p>
  <p><strong>DOB:</strong> <?php echo $user['dob']; ?></p>
  <p><strong>Class:</strong> <?php echo $user['class']; ?></p>
  <p><strong>Course:</strong> <?php echo $user['course']; ?></p>
</div>

<script>
  function toggleProfile() {
    const profileBox = document.getElementById('profilePopup');
    profileBox.classList.toggle('active');
  }
</script>

</body>
</html>
