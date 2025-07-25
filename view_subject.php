<?php
include "config.php";

// Check if subject_id is passed in URL
if (!isset($_GET['subject_id'])) {
    echo "⚠️ No subject selected.";
    exit;
}

$subject_id = intval($_GET['subject_id']);

// Fetch subject name
$subject_query = mysqli_query($conn, "SELECT name FROM subjects WHERE id=$subject_id");
$subject = mysqli_fetch_assoc($subject_query);

// Check if subject exists
if (!$subject) {
    echo "❌ Subject not found.";
    exit;
}

// Fetch question papers
$papers = mysqli_query($conn, "SELECT * FROM question_papers WHERE subject_id=$subject_id");
?>

<h2>Question Papers for <?= htmlspecialchars($subject['name']) ?></h2>

<ul>
    <?php while($row = mysqli_fetch_assoc($papers)) { ?>
        <li><a href="<?= htmlspecialchars($row['file_path']) ?>" target="_blank"><?= htmlspecialchars($row['title']) ?></a></li>
    <?php } ?>
</ul>

<a href="upload_form.php?subject_id=<?= $subject_id ?>">Upload New Question Paper</a>
