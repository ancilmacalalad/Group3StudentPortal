<?php
// Database connection
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'studentportal';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if (isset($_POST['add_user'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact_number'];
    $address = $_POST['address'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $date_of_birth = $_POST['date_of_birth'];
    $student_id = $_POST['student_id'];
    $user_type = $_POST['usertype']; // Hardcoded to "student"

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Profile Picture Upload Handling
        $img_url = null; // Default value for no picture
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
            // Generate unique filename
            $file_extension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
            $unique_filename = uniqid() . '.' . $file_extension;

            // Specify the folder to upload images
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true); // Create the folder if it doesn't exist
            }

            $file_tmp = $_FILES['profile_picture']['tmp_name'];
            $file_path = $upload_dir . $unique_filename;

            // Move the uploaded file to the designated folder
            if (move_uploaded_file($file_tmp, $file_path)) {
                $img_url = $file_path; // Save the local file path
            } else {
                echo "<script>alert('Error uploading profile picture.');</script>";
            }
        }

        // Insert query for adding the new student
        $sql = "INSERT INTO studentportal (first_name, last_name, email, contact_number, address, passcode, usertype, date_of_birth, img_url, student_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Prepare and execute the statement
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssss", $first_name, $last_name, $email, $contact_number, $address, $hashed_password, $user_type, $date_of_birth, $img_url, $student_id);

        if ($stmt->execute()) {
            echo "<script>alert('Student account created successfully.'); window.location.href = 'teachers_dashboard.php';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Student Account</title>
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
</head>
<body>
    <div class="form-container">
        <h2>Create Student Account</h2>
        <form action="add_user.php" method="POST" enctype="multipart/form-data">

            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" id="first_name" placeholder="Enter first name" required>

            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" id="last_name" placeholder="Enter last name" required>

            <label for="student_id">Student ID:</label>
            <input type="text" name="student_id" id="student_id" placeholder="Enter student ID" required>

            <label for="email">Email Address:</label>
            <input type="email" name="email" id="email" placeholder="Enter email address" required>

            <label for="contact_number">Contact Number:</label>
            <input type="text" name="contact_number" id="contact_number" placeholder="Enter contact number" required>

            <label for="address">Address:</label>
            <textarea name="address" id="address" rows="4" placeholder="Enter address" required></textarea>

            <label for="date_of_birth">Date of Birth:</label>
            <input type="date" name="date_of_birth" id="date_of_birth" required>

            <label for="profile_picture">Profile Picture:</label>
            <input type="file" name="profile_picture" id="profile_picture" accept="image/*">

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" placeholder="Enter password" required>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm password" required>

            <label for="user_type">User Type:</label>
            <select name="usertype" id="user_type" required>
                <option value="">Select User Type</option>
                <option value="user">Student</option>
                </select>
            <button type="submit" name="add_user">Create Account</button>
            <a href="teachers_dashboard.php">Back to Home</a>
        </form>
    </div>
</body>
</html>
