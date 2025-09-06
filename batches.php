<?php
session_start();
include "config.php";

// Security: only admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("⛔ Unauthorized");
}

$college_id = intval($_SESSION['college_id']);

// Get department_id
if (!isset($_GET['department_id'])) {
    die("⚠️ Department not specified.");
}
$department_id = intval($_GET['department_id']);

// Fetch department name
$dept_stmt = $conn->prepare("SELECT department_name FROM departments WHERE id = ? AND college_id = ?");
$dept_stmt->bind_param("ii", $department_id, $college_id);
$dept_stmt->execute();
$dept_res = $dept_stmt->get_result();
if ($dept_res->num_rows === 0) {
    die("⚠️ Department not found.");
}
$department = $dept_res->fetch_assoc();
$dept_stmt->close();

// Handle Batch Creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['batch_name'])) {
    $batch_name = trim($_POST['batch_name']);
    if (!empty($batch_name)) {
        // Generate unique class_code (random 6 chars)
        $class_code = strtoupper(substr(md5(uniqid()), 0, 6));

        $stmt = $conn->prepare("INSERT INTO batches (department_id, batch_name, class_code) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $department_id, $batch_name, $class_code);
        $stmt->execute();
        $stmt->close();
        header("Location: batches.php?department_id=$department_id");
        exit;
    }
}

// Fetch batches for this department
$batches_stmt = $conn->prepare("SELECT * FROM batches WHERE department_id = ?");
$batches_stmt->bind_param("i", $department_id);
$batches_stmt->execute();
$batches_res = $batches_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Batches - <?php echo htmlspecialchars($department['department_name']); ?></title>
</head>
<body>
<h1>Batches for <?php echo htmlspecialchars($department['department_name']); ?></h1>

<form method="POST">
    <input type="text" name="batch_name" placeholder="New Batch" required>
    <button type="submit">Create Batch</button>
</form>

<h2>Existing Batches</h2>
<ul>
<?php
while ($batch = $batches_res->fetch_assoc()) {
    echo '<li>';
    echo htmlspecialchars($batch['batch_name']) . ' - Class Code: ' . htmlspecialchars($batch['class_code']);
    echo '</li>';
}
?>
</ul>

<a href="departments.php">Back to Departments</a>
</body>
</html>
