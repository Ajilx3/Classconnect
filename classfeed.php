<?php
session_start();
if (!isset($_SESSION['name'])) {
  header("Location: login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ClassConnect - ClassFeed</title>
  <link rel="stylesheet" href="classfeed.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
    }
    .feed-container {
      max-width: 800px;
      margin: 60px auto;
      padding: 20px;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .feed-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }
    .feed-header h2 {
      margin: 0;
    }
    .feed-header button {
      padding: 8px 14px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
    }
    .post-box-popup {
      display: none;
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0, 0, 0, 0.6);
      justify-content: center;
      align-items: center;
      z-index: 999;
    }
    .post-box {
      background: white;
      padding: 25px;
      border-radius: 10px;
      width: 90%;
      max-width: 500px;
    }
    .post-box h3 {
      margin-top: 0;
    }
    .post-box label {
      display: block;
      margin: 10px 0 5px;
    }
    .post-box input,
    .post-box select,
    .post-box textarea {
      width: 100%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
    .post-box button {
      margin-top: 15px;
      width: 100%;
      padding: 10px;
      background: #28a745;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
    }
    .close-btn {
      float: right;
      cursor: pointer;
      font-size: 18px;
    }
    .post-item {
      background: #fafafa;
      border: 1px solid #ddd;
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 15px;
    }
    .post-meta {
      display: flex;
      justify-content: space-between;
      font-size: 14px;
      color: #555;
      margin-bottom: 10px;
    }
    .post-type {
      font-weight: bold;
      margin-bottom: 6px;
    }
    .file-link a {
      color: #007bff;
      text-decoration: none;
    }
  </style>
</head>
<body>

<div class="feed-container">
  <div class="feed-header">
    <h2>📚 ClassFeed</h2>
    <button onclick="togglePostBox()">+ New Post</button>
  </div>

  <div id="post-feed">
    <!-- All posts will be shown here -->
  </div>
</div>

<!-- Upload Post Popup -->
<div class="post-box-popup" id="postBoxPopup">
  <div class="post-box">
    <span class="close-btn" onclick="togglePostBox()">✖</span>
    <h3>Share something with your class ✨</h3>
    <form action="classfeed_upload.php" method="POST" enctype="multipart/form-data">
      <label for="post_type">Post Type</label>
      <select name="post_type" id="post_type" required>
        <option value="">-- Select Type --</option>
        <option value="notes">📝 Notes</option>
        <option value="announcement">📢 Announcement</option>
        <option value="doubt">❓ Doubt</option>
        <option value="meet_link">🔗 Meet Link</option>
        <option value="other">📁 Other</option>
      </select>

      <label for="message">Message</label>
      <textarea name="message" id="message" rows="4" placeholder="Write something..." required></textarea>

      <label for="file">Optional File Upload</label>
      <input type="file" name="file" id="file" accept=".pdf,.jpg,.jpeg,.png,.docx,.pptx">

      <button type="submit" name="submit">📤 Post</button>
    </form>
  </div>
</div>

<script>
function togglePostBox() {
  const popup = document.getElementById('postBoxPopup');
  popup.style.display = (popup.style.display === 'flex') ? 'none' : 'flex';
}

// Load posts from the backend
async function loadPosts() {
  const res = await fetch('get_classfeed.php');
  const posts = await res.json();
  const container = document.getElementById('post-feed');
  container.innerHTML = '';

  posts.forEach(post => {
    const box = document.createElement('div');
    box.classList.add('post-item');

    let fileSection = '';
    if (post.file_path) {
      fileSection = `
        <div class="file-link">
          📎 <a href="uploads/${post.file_path}" target="_blank">View Attached File</a>
        </div>`;
    }

    box.innerHTML = `
      <div class="post-meta">
        <span><strong>${post.name}</strong> (${post.role})</span>
        <span>${new Date(post.created_at).toLocaleString()}</span>
      </div>
      <div class="post-type">📌 ${post.post_type}</div>
      <p>${post.message}</p>
      ${fileSection}
    `;
    container.appendChild(box);
  });
}

window.onload = loadPosts;
</script>

</body>
</html>
