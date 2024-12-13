<?php
// Database connection
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'studentportal';

$conn = new mysqli($host, $user, $password, $database);

// Check if connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and validate form data
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $student_id = $_POST['student_id'] ?? '';
    $email = $_POST['email'] ?? '';
    $contact_number = $_POST['contact_number'] ?? '';
    $address = $_POST['address'] ?? '';
    $date_of_birth = $_POST['date_of_birth'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $user_type = $_POST['usertype'] ?? '';
    $program = $_POST['program'] ?? '';
    $section = $_POST['section'] ?? '';

    // Basic validation
    if (
        empty($first_name) || empty($last_name) || empty($student_id) || 
        empty($email) || empty($contact_number) || empty($address) || 
        empty($date_of_birth) || empty($password) || empty($confirm_password) || 
        empty($user_type) || empty($program) || empty($section)
    ) {
        echo '<div style="color: red;">All fields are required!</div>';
        exit;
    }

    if ($password !== $confirm_password) {
        echo '<div style="color: red;">Passwords do not match!</div>';
        exit;
    }

    // Profile Picture Upload
    $img_url = null;
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $upload_dir = 'uploads/';
        $file_name = basename($_FILES['profile_picture']['name']);
        $file_tmp = $_FILES['profile_picture']['tmp_name'];
        $file_path = $upload_dir . $file_name;

        if (move_uploaded_file($file_tmp, $file_path)) {
            $img_url = $file_path;
        } else {
            echo '<div style="color: red;">Error uploading profile picture.</div>';
            exit;
        }
    }

    // Insert data into database
    $sql = "INSERT INTO studentportal 
            (first_name, last_name, student_id, email, contact_number, address, date_of_birth, passcode, usertype, program, section, img_url) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param(
            "ssssssssssss", 
            $first_name, $last_name, $student_id, $email, $contact_number, $address, 
            $date_of_birth, $password, $user_type, $program, $section, $img_url
        );

        if ($stmt->execute()) {
            echo '<div style="color: green;">Account created successfully!</div>';
        } else {
            echo '<div style="color: red;">Error: ' . $stmt->error . '</div>';
        }

        $stmt->close();
    } else {
        echo '<div style="color: red;">Error preparing statement: ' . $conn->error . '</div>';
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
</head>
<style>
         body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .form-container {
            background-color: #ffffff;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            border-radius: 10px;
            padding: 30px;
            width: 100%;
            max-width: 450px;
            text-align: center;
        }

        .form-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333333;
        }

        label {
            display: block;
            text-align: left;
            margin-bottom: 8px;
            font-weight: bold;
            color: #444444;
        }

        input[type="text"], 
        input[type="email"], 
        input[type="password"], 
        input[type="date"], 
        input[type="file"], 
        textarea {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 20px;
            border: 1px solid #cccccc;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus, 
        input[type="email"]:focus, 
        input[type="password"]:focus, 
        input[type="date"]:focus, 
        input[type="file"]:focus, 
        textarea:focus {
            border-color: #6a11cb;
            outline: none;
        }

        textarea {
            resize: none;
        }

        button {
            background-color: #6a11cb;
            color: #ffffff;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #2575fc;
        }

        .form-container a {
            display: block;
            margin-top: 15px;
            text-decoration: none;
            color: #6a11cb;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .form-container a:hover {
            color: #2575fc;
        }
    </style>
<body>
    <form method="POST" enctype="multipart/form-data" action="">
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" required>

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" required>

        <label for="student_id">Student ID:</label>
        <input type="text" id="student_id" name="student_id" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="contact_number">Contact Number:</label>
        <input type="text" id="contact_number" name="contact_number" required>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required>

        <label for="date_of_birth">Date of Birth:</label>
        <input type="date" id="date_of_birth" name="date_of_birth" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>

        <label for="usertype">User Type:</label>
        <select id="usertype" name="usertype" required>
            <option value="user">Student</option>
        
        </select>

        <label for="program">Program:</label>
        <select id="program" name="program" required>
            <option value="STEM">STEM</option>
            <option value="ABM">ABM</option>
            <option value="HUMSS">HUMSS</option>
        </select>

        <label for="section">Section:</label>
        <select id="section" name="section" required>
            <option value="STEM 1">STEM 1</option>
            <option value="STEM 2">STEM 2</option>
            <option value="ABM 1">ABM 1</option>
            <option value="HUMSS 1">HUMSS 1</option>
        </select>

        <label for="profile_picture">Profile Picture:</label>
        <input type="file" id="profile_picture" name="profile_picture">

        <button type="submit">Add User</button>
    </form>
</body>
</html>
