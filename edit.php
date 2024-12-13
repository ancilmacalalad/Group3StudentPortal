<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "studentportal";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize $student to avoid undefined variable warnings
$student = null;
$error_message = '';

// Improve error handling and redirection
if (!isset($_GET['student_id']) || empty($_GET['student_id'])) {
    $_SESSION['error_message'] = "Student ID not provided.";
    header("Location: manage_students.php");
    exit();
}

// Fetch student data by ID
if (isset($_GET['student_id']) && !empty($_GET['student_id'])) {
    $student_id = intval($_GET['student_id']); // Ensure it's an integer
    $sql = "SELECT * FROM studentportal WHERE student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
    } else {
        $error_message = "Student not found.";
    }
} else {
    $error_message = "Student ID not provided.";
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $student !== null) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $contact_number = $_POST['contact_number'];
    $address = $_POST['address'];
    $email = $_POST['email'];

    // Input validation
    if (empty($first_name) || empty($last_name) || empty($contact_number) || empty($address) || empty($email)) {
        $error_message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } elseif (!preg_match("/^[0-9]{10,11}$/", $contact_number)) {
        $error_message = "Invalid contact number format. It should be 10-11 digits.";
    } else {
        // Update query to update student details
        $updateSql = "UPDATE studentportal SET first_name = ?, last_name = ?, contact_number = ?, address = ?, email = ? WHERE student_id = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("sssssi", $first_name, $last_name, $contact_number, $address, $email, $student_id);

        if ($stmt->execute()) {
            // Refresh student data
            $stmt = $conn->prepare("SELECT * FROM studentportal WHERE student_id = ?");
            $stmt->bind_param("i", $student_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $student = $result->fetch_assoc();

            $_SESSION['success_message'] = "Student information updated successfully!";
        } else {
            $error_message = "Error updating record: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .form-container {
            width: 50%;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 16px;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="text"], input[type="tel"] {
            padding: 10px;
            font-size: 16px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            padding: 10px 15px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        a {
            text-decoration: none;
            color: #3498db;
            font-size: 14px;
            display: block;
            text-align: center;
            margin-top: 15px;
        }

        a:hover {
            text-decoration: underline;
        }

        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            text-align: center;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Edit Student Information</h2>

    <?php
    if (isset($_SESSION['success_message'])) {
        echo "<div class='message success'>" . htmlspecialchars($_SESSION['success_message']) . "</div>";
        unset($_SESSION['success_message']);
    }
    if (isset($_SESSION['error_message'])) {
        echo "<div class='message error'>" . htmlspecialchars($_SESSION['error_message']) . "</div>";
        unset($_SESSION['error_message']);
    } elseif (!empty($error_message)) {
        echo "<div class='message error'>" . htmlspecialchars($error_message) . "</div>";
    }
    ?>
    
    <?php if ($student !== null): ?>
        <form method="POST" action="">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" 
                   value="<?php echo htmlspecialchars($student['first_name']); ?>" required>

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" 
                   value="<?php echo htmlspecialchars($student['last_name']); ?>" required>

            <label for="contact_number">Contact Number:</label>
            <input type="tel" id="contact_number" name="contact_number" 
                   value="<?php echo htmlspecialchars($student['contact_number']); ?>" pattern="[0-9]{10,11}" required>
            <small style="color: #888;">Format: 10-11 digits</small>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" 
                   value="<?php echo htmlspecialchars($student['address']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" 
                   value="<?php echo htmlspecialchars($student['email']); ?>" required>

            <button type="submit">Update Information</button>
        </form>
    <?php endif; ?>
    <a href="manage_students.php">Back to Manage Students</a>
</div>

</body>
</html>