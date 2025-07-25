<?php
include "config.php";
session_start();

// Fetch user data to edit
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE id='$id'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    } else {
        echo "User not found!";
        exit();
    }
}

// Handle form submission
if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $admission_no = $_POST['admission_no'];
    $email = $_POST['email'];
    $register_no = $_POST['register_no'];
    $dob = $_POST['dob'];
    $class = $_POST['class'];
    $course = $_POST['course'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // If password field is filled, hash and update it
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET 
                name='$name',
                admission_no='$admission_no',
                email='$email',
                register_no='$register_no',
                dob='$dob',
                class='$class',
                course='$course',
                password='$hashed_password',
                role='$role'
                WHERE id='$id'";
    } else {
        // Don't change the password if left blank
        $sql = "UPDATE users SET 
                name='$name',
                admission_no='$admission_no',
                email='$email',
                register_no='$register_no',
                dob='$dob',
                class='$class',
                course='$course',
                role='$role'
                WHERE id='$id'";
    }

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo "Update failed: " . mysqli_error($conn);
    } else {
        header("Location: userslist.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User (Admin Panel)</title>
        <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", sans-serif;
        }

        body {
            background-color: #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .form-container {
            background-color: #fff;
            padding: 30px 40px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            color: #555;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
            transition: 0.3s ease;
        }

        .form-group input:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
        }

        .form-submit {
            margin-top: 20px;
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            border: none;
            color: white;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-submit:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h2>Edit User Details</h2>
    <form action="" method="post">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

        Name: <input type="text" name="name" value="<?php echo $row['name']; ?>" required><br><br>
        Admission No: <input type="number" name="admission_no" value="<?php echo $row['admission_no']; ?>"><br><br>
        Email: <input type="email" name="email" value="<?php echo $row['email']; ?>"><br><br>
        Register No: <input type="number" name="register_no" value="<?php echo $row['register_no']; ?>"><br><br>
        Date of Birth: <input type="date" name="dob" value="<?php echo $row['dob']; ?>"><br><br>
        Class: <input type="text" name="class" value="<?php echo $row['class']; ?>"><br><br>
        Course: <input type="text" name="course" value="<?php echo $row['course']; ?>"><br><br>
        Password: <input type="text" name="password" placeholder="Leave blank to keep old password"><br><br>
        Role: <input type="text" name="role" value="<?php echo $row['role']; ?>"><br><br>

        <input type="submit" name="submit" value="Update">
    </form>
</body>
</html>
