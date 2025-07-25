<!-- add_subject.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Subject</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 30px;
    }
    input[type="text"] {
      padding: 10px;
      width: 250px;
      margin-right: 10px;
    }
    button {
      padding: 10px 15px;
      background-color: #28a745;
      color: white;
      border: none;
      cursor: pointer;
      border-radius: 5px;
    }
  </style>
</head>
<body>
  <h2>Add New Subject</h2>
  <form action="add_subject_handler.php" method="post">
    <input type="text" name="subject_name" placeholder="Enter subject name" required>
    <button type="submit">Add Subject</button>
  </form>
</body>
</html>
