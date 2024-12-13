<?php
session_start();
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
if (isset($_POST['update'])) {
    $student_id = $_POST['student_id']; // Get from form input
    
    // First verify if student ID exists
    $verify_id_sql = "SELECT student_id FROM studentportal WHERE student_id = ?";
    $verify_id_stmt = $conn->prepare($verify_id_sql);
    $verify_id_stmt->bind_param("s", $student_id);
    $verify_id_stmt->execute();
    $result = $verify_id_stmt->get_result();
    
    if ($result->num_rows === 0) {
        $_SESSION['error'] = '<div class="message error">
            <i class="fas fa-exclamation-circle"></i>
            Student ID not found
        </div>';
        header("Location: update_data.php");
        exit();
    }

    $contact_number = $_POST['contact_number'];
    $address = $_POST['address'];
    $img_url = null;
    $update_password = false;
    $old_password = $_POST['old_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Handle file upload for profile picture
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $target_dir = "uploads/";
        $img_url = $target_dir . basename($_FILES['profile_picture']['name']);

        // Move uploaded file
        if (!move_uploaded_file($_FILES['profile_picture']['tmp_name'], $img_url)) {
            die("Failed to upload profile picture.");
        }
    }

    // Password change logic
    if (!empty($new_password) || !empty($confirm_password)) {
        // First check if new password and confirm password match
        if ($new_password !== $confirm_password) {
            $_SESSION['error'] = '<div class="message error">
                <i class="fas fa-exclamation-circle"></i>
                PLEASE MATCH THE NEW PASSWORD
            </div>';
            header("Location: update_data.php");
            exit();
        }

        // Then verify old password
        $verify_sql = "SELECT passcode FROM studentportal WHERE student_id = ? AND passcode = ?";
        $verify_stmt = $conn->prepare($verify_sql);
        $verify_stmt->bind_param("ss", $student_id, $old_password);
        $verify_stmt->execute();
        $result = $verify_stmt->get_result();
        
        if ($result->num_rows === 0) {
            $_SESSION['error'] = '<div class="message error">
                <i class="fas fa-exclamation-circle"></i>
                Incorrect old password
            </div>';
            header("Location: update_data.php");
            exit();
        }

        // If we get here, both validations passed
        $update_password = true;
        $new_password_to_store = $new_password;
    }

    // Update query - only update fields that were filled
    $sql = "UPDATE studentportal SET";
    $params = [];
    $types = "";

    if (!empty($contact_number)) {
        $sql .= " contact_number = ?,";
        $params[] = $contact_number;
        $types .= "s";
    }

    if (!empty($address)) {
        $sql .= " address = ?,";
        $params[] = $address;
        $types .= "s";
    }

    if ($img_url) {
        $sql .= " img_url = ?,";
        $params[] = $img_url;
        $types .= "s";
    }

    if ($update_password) {
        $sql .= " passcode = ?,";
        $params[] = $new_password_to_store;
        $types .= "s";
    }

    // Remove trailing comma
    $sql = rtrim($sql, ",");

    $sql .= " WHERE student_id = ?";
    $params[] = $student_id;
    $types .= "s";

    // Prepare and execute the statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        $_SESSION['success'] = true;
        header("Location: update_data.php");
        exit();
    } else {
        $_SESSION['error'] = '<div class="message error">
            <i class="fas fa-exclamation-circle"></i>
            Error: ' . $stmt->error . '
        </div>';
        header("Location: update_data.php");
        exit();
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            background: url('https://scontent.fmnl30-2.fna.fbcdn.net/v/t1.6435-9/144479337_100276648761424_1300734801011337603_n.jpg?_nc_cat=110&ccb=1-7&_nc_sid=86c6b0&_nc_eui2=AeEJntlOHIjNwx9MhwiHnDU-R1fQJgdTMN9HV9AmB1Mw3xW9WxJqEvFjvBDYnohuPLwCCUXHQbYiNOoynb9OQfG4&_nc_ohc=Cx8D0-h-HgcQ7kNvgHb12Je&_nc_zt=23&_nc_ht=scontent.fmnl30-2.fna&_nc_gid=AHVqySvdJUJh2_Waj9J6gS4&oh=00_AYDcxEfTxCEiuFDYE8F7qcxzPlYV2XQfkKOU0OU2XgOxWQ&oe=67393F98') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            margin: 20px;
        }

        h2 {
            color: #4a5568;
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: 700;
        }

        label {
            color: #4a5568;
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
        }

        input[type="text"],
        input[type="password"],
        input[type="file"],
        textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        input:focus,
        textarea:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        button {
            background: linear-gradient(to right, #667eea, #764ba2);
            color: white;
            padding: 15px;
            border: none;
            border-radius: 8px;
            width: 100%;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #764ba2;
        }

        /* Add success/error message styling */
        .message {
            padding: 15px 20px;
            margin: 20px auto;
            border-radius: 8px;
            text-align: center;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            max-width: 400px;
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .error {
            background-color: #FEE2E2;
            color: #DC2626;
            border: 1px solid #FCA5A5;
        }

        .error i {
            color: #DC2626;
            font-size: 18px;
        }

        .success {
            background-color: #D1FAE5;
            color: #059669;
            border: 1px solid #6EE7B7;
        }

        .success i {
            color: #059669;
            font-size: 18px;
        }

        /* Add this for better message visibility */
        .message i {
            margin-right: 8px;
        }

        /* Add hover effect */
        .message:hover {
            transform: translateY(-2px);
            transition: transform 0.2s ease;
        }

        .back-button {
            display: inline-block;
            padding: 10px 25px;
            background: linear-gradient(to right, #667eea, #764ba2);
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: transform 0.2s ease;
        }

        .back-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            color: white;
        }
    </style>

    <!-- Make sure you have Font Awesome included -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php
    if(isset($_SESSION['success'])) {
        ?>
        <div class="form-container" style="text-align: center;">
            <h2 style="color: #059669; margin-bottom: 30px;">UPDATED SUCCESSFULLY</h2>
            <a href="student_dashboard.php" class="back-button">Back</a>
        </div>
        <?php
        unset($_SESSION['success']);
    } else {
        // Show the form only if not successful
    ?>
    <div class="form-container">
        <h2>Update Profile</h2>
        
        <?php
        if(isset($_SESSION['error'])) {
            echo $_SESSION['error'];
            unset($_SESSION['error']);
        }
        ?>

        <form action="update_data.php" method="POST" enctype="multipart/form-data">
            <label for="student_id">Student ID:</label>
            <input type="text" name="student_id" id="student_id" placeholder="Enter your Student ID" required>

            <label for="old_password">Old Password:</label>
            <input type="password" name="old_password" id="old_password" placeholder="Enter old password" required>

            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" id="new_password" placeholder="Enter new password">

            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm new password">

            <label for="profile_picture">Profile Picture: (Optional)</label>
            <input type="file" name="profile_picture" id="profile_picture">

            <label for="contact_number">Contact Number: (Optional)</label>
            <input type="text" name="contact_number" id="contact_number">

            <label for="address">Address: (Optional)</label>
            <textarea name="address" id="address" rows="4"></textarea>

            <button type="submit" name="update">Update Profile</button>
            <a href="student_dashboard.php">Back to Home</a>
        </form>
    </div>
    <?php } ?>
</body>
</html>
