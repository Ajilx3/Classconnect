<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id']) || !isset($_SESSION['batch_id']) || !isset($_SESSION['college_id'])) {
    die("❌ Session expired. Please log in again.");
}

$user_id    = $_SESSION['user_id'];
$batch_id   = $_SESSION['batch_id'];
$college_id = $_SESSION['college_id'];

// ✅ Fetch available file types
$typeResult = $conn->query("SELECT * FROM file_types ORDER BY type_name ASC");

// ✅ Handle Delete Request
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);

    // Make sure this file belongs to the current student
    $stmt = $conn->prepare("SELECT file_path FROM files WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $delete_id, $user_id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($file = $res->fetch_assoc()) {
        if (file_exists($file['file_path'])) {
            unlink($file['file_path']); // Delete file from server
        }
        $stmt->close();

        $deleteStmt = $conn->prepare("DELETE FROM files WHERE id = ? AND user_id = ?");
        $deleteStmt->bind_param("ii", $delete_id, $user_id);
        $deleteStmt->execute();
        $deleteStmt->close();

        echo "<script>alert('🗑️ File deleted successfully'); window.location.href='upload_file.php';</script>";
        exit;
    }
}

// ✅ Handle Edit Request (Update File Name & Type)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['edit_id'])) {
    $edit_id = intval($_POST['edit_id']);
    $newName = mysqli_real_escape_string($conn, $_POST['edit_file_name']);
    $newTypeId = intval($_POST['edit_file_type']);

    // Get type name
    $typeQuery = $conn->prepare("SELECT type_name FROM file_types WHERE id = ?");
    $typeQuery->bind_param("i", $newTypeId);
    $typeQuery->execute();
    $typeResultRow = $typeQuery->get_result()->fetch_assoc();
    $fileTypeName = $typeResultRow ? $typeResultRow['type_name'] : 'Unknown';
    $typeQuery->close();

    $updateStmt = $conn->prepare("UPDATE files SET file_name = ?, title = ? WHERE id = ? AND user_id = ?");
    $updateStmt->bind_param("ssii", $newName, $fileTypeName, $edit_id, $user_id);
    $updateStmt->execute();
    $updateStmt->close();

    echo "<script>alert('✏️ File updated successfully'); window.location.href='upload_file.php';</script>";
    exit;
}

// ✅ Handle file upload
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["file"]) && !isset($_POST['edit_id'])) {
    $fileName = mysqli_real_escape_string($conn, $_POST['file_name']);
    $fileTypeId = intval($_POST['file_type']);

    // Get type name from table
    $typeQuery = $conn->prepare("SELECT type_name FROM file_types WHERE id = ?");
    $typeQuery->bind_param("i", $fileTypeId);
    $typeQuery->execute();
    $typeResultRow = $typeQuery->get_result()->fetch_assoc();
    $fileTypeName = $typeResultRow ? $typeResultRow['type_name'] : 'Unknown';
    $typeQuery->close();

    $uploadDir = "uploads/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $uniqueName = time() . "_" . basename($_FILES["file"]["name"]);
    $filePath = $uploadDir . $uniqueName;

    if (move_uploaded_file($_FILES["file"]["tmp_name"], $filePath)) {
        $stmt = $conn->prepare("
            INSERT INTO files (user_id, file_name, title, file_type, file_path, college_id, batch_id, uploaded_at) 
            VALUES (?, ?, ?, 'assignment', ?, ?, ?, NOW())
        ");
        $stmt->bind_param("isssii", $user_id, $fileName, $fileTypeName, $filePath, $college_id, $batch_id);

        if ($stmt->execute()) {
            echo "<script>alert('✅ File uploaded successfully'); window.location.href='upload_file.php';</script>";
        } else {
            echo "<script>alert('❌ Database error: ".$stmt->error."');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('❌ File upload failed');</script>";
    }
}

// ✅ Fetch student's own uploads
$myFiles = $conn->prepare("
    SELECT id, title, file_name, file_type, file_path, uploaded_at 
    FROM files 
    WHERE batch_id = ? AND college_id = ? AND user_id = ?
    ORDER BY uploaded_at DESC
");
$myFiles->bind_param("iii", $batch_id, $college_id, $user_id);
$myFiles->execute();
$myFilesResult = $myFiles->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Files</title>
</head>
<body>
<link rel="stylesheet" href="upload_files.css">
<h2>📤 Upload Your File</h2>
<form method="post" enctype="multipart/form-data">
    <input type="text" name="file_name" placeholder="Enter File Name" required><br><br>

    <select name="file_type" required>
        <option value="">Select Type</option>
        <?php
        $typeResult->data_seek(0); // Reset pointer
        while($type = $typeResult->fetch_assoc()) { ?>
            <option value="<?= $type['id'] ?>"><?= htmlspecialchars($type['type_name']) ?></option>
        <?php } ?>
    </select><br><br>

    <input type="file" name="file" required><br><br>
    <button type="submit">Upload</button>
</form>

<hr>

<h2>📑 Your Uploaded Files</h2>
<?php if ($myFilesResult->num_rows > 0): ?>
<table border="1" cellpadding="8">
    <tr>
        <th>Type</th>
        <th>File Name</th>
        <th>Uploaded At</th>
        <th>Actions</th>
    </tr>
    <?php while($row = $myFilesResult->fetch_assoc()) { ?>
    <tr>
        <td><?= htmlspecialchars($row['title']) ?></td>
        <td><?= htmlspecialchars($row['file_name']) ?></td>
        <td><?= $row['uploaded_at'] ?></td>
        <td>
            <a href="<?= $row['file_path'] ?>" target="_blank">Download</a> | 
            <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this file?');">🗑️ Delete</a> |
            <a href="#" onclick="editFile(<?= $row['id'] ?>, '<?= htmlspecialchars($row['file_name'], ENT_QUOTES) ?>'); return false;">✏️ Edit</a>
        </td>
    </tr>
    <?php } ?>
</table>
<?php else: ?>
<p>You haven't uploaded any files yet.</p>
<?php endif; ?>

<!-- Simple edit form (hidden until clicked) -->
<div id="editForm" style="display:none; margin-top:20px;">
    <h3>✏️ Edit File</h3>
    <form method="post">
        <input type="hidden" name="edit_id" id="edit_id">
        <input type="text" name="edit_file_name" id="edit_file_name" required><br><br>

        <select name="edit_file_type" required>
            <option value="">Select Type</option>
            <?php
            $typeResult->data_seek(0);
            while($type = $typeResult->fetch_assoc()) { ?>
                <option value="<?= $type['id'] ?>"><?= htmlspecialchars($type['type_name']) ?></option>
            <?php } ?>
        </select><br><br>

        <button type="submit">Save Changes</button>
        <button type="button" onclick="document.getElementById('editForm').style.display='none';">Cancel</button>
    </form>
</div>

<script>
function editFile(id, fileName) {
    document.getElementById('editForm').style.display = 'block';
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_file_name').value = fileName;
}
</script>

</body>
</html>
