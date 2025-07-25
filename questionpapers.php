<!-- questionpapers.php -->
<?php
include "config.php";
$result = $conn->query("SELECT * FROM subjects");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Subjects</title>
  <style>
    .subject-block {
      padding: 20px;
      margin: 15px;
      background-color: #f0f0f0;
      border-radius: 12px;
      text-align: center;
      font-size: 18px;
      cursor: pointer;
      box-shadow: 0 3px 6px rgba(0,0,0,0.1);
      transition: 0.3s;
    }
    .subject-block:hover {
      background-color: #dcdcdc;
    }
    .container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
    }
    .upload-btn {
      position: fixed;
      bottom: 30px;
      right: 30px;
      padding: 15px 25px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 50px;
      font-size: 16px;
      cursor: pointer;
    }
  </style>
</head>
<body>

  <h2 style="text-align:center;">Available Subjects</h2>
    <div class="container">
  <?php while ($sub = mysqli_fetch_assoc($result)) { ?>
    <div class="subject-block" onclick="window.location.href='view_subject.php?subject_id=<?= $sub['id'] ?>'">
      <?= htmlspecialchars($sub['name']) ?>
    </div>
  <?php } ?>
</div>

  <button onclick="window.location.href='add_subject.php'">Add Subjects</button>


</body>
</html>
