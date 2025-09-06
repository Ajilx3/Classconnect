<?php
session_start();
include "config.php";

// ✅ Only teachers can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    die("⛔ Unauthorized Access");
}

// ✅ Handle Add
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['type_name']) && !isset($_POST['edit_id'])) {
    $type_name = trim($_POST['type_name']);

    if (!empty($type_name)) {
        $stmt = $conn->prepare("INSERT INTO file_types (type_name) VALUES (?)");
        $stmt->bind_param("s", $type_name);

        if ($stmt->execute()) {
            echo "<script>alert('✅ File Type Added'); window.location.href='create_file_type.php';</script>";
        } else {
            if ($conn->errno === 1062) {
                echo "<script>alert('⚠️ File type already exists!');</script>";
            } else {
                echo "<script>alert('❌ Database Error: " . $conn->error . "');</script>";
            }
        }
        $stmt->close();
    }
}

// ✅ Handle Edit
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['edit_id'])) {
    $edit_id = intval($_POST['edit_id']);
    $edit_name = trim($_POST['type_name']);

    if (!empty($edit_name)) {
        $stmt = $conn->prepare("UPDATE file_types SET type_name = ? WHERE id = ?");
        $stmt->bind_param("si", $edit_name, $edit_id);

        if ($stmt->execute()) {
            echo "<script>alert('✅ File Type Updated'); window.location.href='create_file_type.php';</script>";
        } else {
            echo "<script>alert('❌ Error updating file type');</script>";
        }
        $stmt->close();
    }
}

// ✅ Handle Delete
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM file_types WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        echo "<script>alert('🗑️ File Type Deleted'); window.location.href='create_file_type.php';</script>";
    }
    $stmt->close();
}

// ✅ Fetch file types
$result = $conn->query("SELECT * FROM file_types ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage File Types</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">📂 Manage File Types</h2>

    <!-- 🔙 Back Button -->
    <div class="mb-3">
        <a href="files_admin.php" class="btn btn-secondary">⬅ Back to File Admin</a>
    </div>

    <!-- Add File Type Form -->
    <form method="POST" class="mb-4">
        <div class="input-group">
            <input type="text" name="type_name" class="form-control" placeholder="Enter new file type..." required>
            <button type="submit" class="btn btn-primary">➕ Add</button>
        </div>
    </form>

    <!-- Show Existing File Types -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>File Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['type_name']) ?></td>
                        <td>
                            <!-- Edit -->
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="edit_id" value="<?= $row['id'] ?>">
                                <input type="text" name="type_name" value="<?= htmlspecialchars($row['type_name']) ?>" required>
                                <button type="submit" class="btn btn-sm btn-warning">✏️ Edit</button>
                            </form>
                            <!-- Delete -->
                            <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">🗑️ Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="3" class="text-center">No file types found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
