<?php
// Database connection
$servername = "localhost";
$username = "root";     // Default XAMPP username
$password = "";         // Default XAMPP password is blank
$dbname = "studentportal";     // Fixed database name to "studentportal"

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    die("Database connection error: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $emergency_name = $conn->real_escape_string($_POST['emergency_name']);
    $emergency_relation = $conn->real_escape_string($_POST['relation']);
    $emergency_contact = $conn->real_escape_string($_POST['emergency_contact']);
    $father_name = $conn->real_escape_string($_POST['father_name']);
    $father_occupation = $conn->real_escape_string($_POST['father_occupation']);
    $mother_name = $conn->real_escape_string($_POST['mother_name']);
    $mother_occupation = $conn->real_escape_string($_POST['mother_occupation']);

    // Prepare SQL statement
    $sql = "INSERT INTO student (
        emergency_name, 
        emergency_relation, 
        emergency_contact, 
        father_name, 
        father_occupation, 
        mother_name, 
        mother_occupation
    ) VALUES (?, ?, ?, ?, ?, ?, ?)";

    // Prepare and bind
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", 
        $emergency_name,
        $emergency_relation,
        $emergency_contact,
        $father_name,
        $father_occupation,
        $mother_name,
        $mother_occupation
    );

    // Execute the statement
    if ($stmt->execute()) {
        echo "Registration successful!";
        // You can redirect to another page here if needed
        // header("Location: success.php");
        // exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Another Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('school_background.jpg'); /* Replace with your background image */
            background-size: cover;
            background-position: center;
            margin: 0;
            padding: 0;
        }
        .form-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            max-width: 500px;
            margin: 50px auto;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Another Registration</h2>
        <form action="nextregistration.php" method="post" enctype="multipart/form-data">
                <legend>Contact in Case of Emergency</legend>
                <label for="emergency_name">Complete Name:</label>
                <input type="text" id="emergency_name" name="emergency_name" required>

                <label for="relation">Relation:</label>
                <select id="relation" name="relation" required>
                    <option value="Parent">Parent</option>
                    <option value="Guardian">Guardian</option>
                    <option value="Other">Other</option>
                </select>

                <label for="emergency_contact">Contact Number:</label>
                <input type="text" id="emergency_contact" name="emergency_contact" required>
            </fieldset>

            <!-- Parents' Information Section -->
            <fieldset>
                <legend>Parents' Information</legend>
                <label for="father_name">Father's Name:</label>
                <input type="text" id="father_name" name="father_name" required>

                <label for="father_occupation">Father's Occupation:</label>
                <input type="text" id="father_occupation" name="father_occupation">

                <label for="mother_name">Mother's Name:</label>
                <input type="text" id="mother_name" name="mother_name" required>

                <label for="mother_occupation">Mother's Occupation:</label>
                <input type="text" id="mother_occupation" name="mother_occupation">
            </fieldset>

            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>
