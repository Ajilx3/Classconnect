<?php
session_start();
include "config.php";

// Only students can vote
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
  echo "🚫 Access denied!";
  exit();
}

$user_id = $_SESSION['user_id']; // or any unique user ID
$poll_id = isset($_GET['poll_id']) ? intval($_GET['poll_id']) : 0;

// Check if poll exists & active
$sql = "SELECT * FROM polls WHERE id = ? AND expires_at > NOW()";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $poll_id);
$stmt->execute();
$poll = $stmt->get_result()->fetch_assoc();

if (!$poll) {
  echo "⚠️ Poll not found or expired.";
  exit();
}

// Check if user already voted
$check = $conn->prepare("SELECT * FROM poll_votes WHERE poll_id = ? AND user_id = ?");
$check->bind_param("is", $poll_id, $user_id);
$check->execute();
$voted = $check->get_result()->num_rows > 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Vote - Poll</title>
  <link rel="stylesheet" href="poll.css">
</head>
<body>
  <div class="poll-container">
    <h2>🗳️ <?= htmlspecialchars($poll['question']) ?></h2>

    <?php if ($voted): ?>
      <p>✅ You’ve already voted in this poll.</p>
      <a href="poll_results.php?poll_id=<?= $poll_id ?>">📊 View Results</a>
    <?php else: ?>
      <form action="submit_vote.php" method="POST">
        <input type="hidden" name="poll_id" value="<?= $poll_id ?>">
        <?php
        $option_stmt = $conn->prepare("SELECT * FROM poll_options WHERE poll_id = ?");
        $option_stmt->bind_param("i", $poll_id);
        $option_stmt->execute();
        $options = $option_stmt->get_result();

        while ($row = $options->fetch_assoc()):
        ?>
          <label>
            <input
              type="<?= $poll['poll_type'] === 'multiple' ? 'checkbox' : 'radio' ?>"
              name="option_ids[]"
              value="<?= $row['id'] ?>"
              required
            >
            <?= htmlspecialchars($row['option_text']) ?>
          </label><br>
        <?php endwhile; ?>

        <br><button type="submit">✅ Submit Vote</button>
      </form>
    <?php endif; ?>
  </div>
</body>
</html>
