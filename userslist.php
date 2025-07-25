<?php
include "config.php";
session_start();
$sql = "SELECT * FROM users";
$result = mysqli_query($conn, $sql);

// Check if query fails
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
} else {
    echo "<h2 style='text-align:center;'>All Registered Users</h2>";
    echo "<table border='1' cellpadding='10' cellspacing='0' style='margin: auto; border-collapse: collapse; font-family: Arial;'>";
    echo "<tr style='background-color: #2c3e50; color: white;'>
            <th>ID</th>
            <th>Name</th>
            <th>Admission No</th>
            <th>Email</th>
            <th>Register No</th>
            <th>DOB</th>
            <th>Class</th>
            <th>Course</th>
            <th>Created At</th>
            <th>Role</th>
            <th>Delete</th>
            <th>Edit</th>
        </tr>";

    // Loop through all users
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>" . $row['id'] . "</td>
                <td>" . htmlspecialchars($row['name']) . "</td>
                <td>" . $row['admission_no'] . "</td>
                <td>" . $row['email'] . "</td>
                <td>" . $row['register_no'] . "</td>
                <td>" . $row['dob'] . "</td>
                <td>" . $row['class'] . "</td>
                <td>" . $row['course'] . "</td>
                <td>" . $row['created_at'] . "</td>
                <td>" . $row['role'] . "</td>
                <td><a href='usersdelete.php?id=" . $row['id'] . "' style='color: red;'>Delete</a></td>
                <td><a href='usersedit.php?id=" . $row['id'] . "' style='color: green;'>Edit</a></td>
            </tr>";
    }

    echo "</table>";
}
?>
