<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "studentportal";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// At the top after database connection
session_start();
$teacherProgram = $_SESSION['program'] ?? ''; // Get the logged-in teacher's program

// Fetch the list of students to display in the dropdown
$studentsSql = "SELECT student_id, first_name, last_name, program, grades, subject FROM studentportal WHERE usertype = 'user' ORDER BY program, last_name, first_name";
$studentsResult = $conn->query($studentsSql);

$successMessage = ""; // Variable to store success message

// Handle form submission to insert the grade
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['student_id']) && isset($_POST['grade']) && isset($_POST['subject'])) {
    $student_id = $_POST['student_id'];
    $grade = $_POST['grade'];
    $subject = $_POST['subject'];

    // First, get the existing grades and subjects
    $getExisting = "SELECT grades, subject FROM studentportal WHERE student_id = ?";
    $stmt = $conn->prepare($getExisting);
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    // Append new grade and subject to existing ones with newline
    $newGrades = !empty($row['grades']) ? $row['grades'] . "\n" . $grade : $grade;
    $newSubjects = !empty($row['subject']) ? $row['subject'] . "\n" . $subject : $subject;

    // Update with concatenated values
    $sql = "UPDATE studentportal SET grades = ?, subject = ? WHERE student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $newGrades, $newSubjects, $student_id);
    
    if ($stmt->execute()) {
        $successMessage = "Grade successfully entered for $subject!";
    } else {
        $successMessage = "Error: " . $stmt->error;
    }
}

if (isset($_POST['subject']) && isset($_POST['grade'])) {
    $newSubject = $_POST['subject'];
    $newGrade = $_POST['grade'];
    
    // Get existing data
    $stmt = $conn->prepare("SELECT subject, grades FROM studentportal WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    // Append new values
    $subjects = !empty($row['subject']) ? $row['subject'] . ";" . $newSubject : $newSubject;
    $grades = !empty($row['grades']) ? $row['grades'] . ";" . $newGrade : $newGrade;
    
    // Update database
    $updateStmt = $conn->prepare("UPDATE studentportal SET subject = ?, grades = ? WHERE email = ?");
    $updateStmt->bind_param("sss", $subjects, $grades, $email);
    $updateStmt->execute();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Grades</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            margin: 20px;
        }

        .form-container {
            max-width: 500px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .form-container label {
            display: block;
            margin-bottom: 10px;
        }

        .form-container input, .form-container select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .form-container button {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        .form-container button:hover {
            background-color: #45a049;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            text-align: center;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            text-align: center;
        }

        /* Add space between buttons */
        .form-container .cta-button {
            margin-top: 15px; /* Space above the Back to Home button */
        }
    </style>
</head>
<body>

<h2>Enter Grades for Students</h2>

<div class="form-container">
    <?php if ($successMessage): ?>
        <div class="<?= strpos($successMessage, 'successfully') !== false ? 'success-message' : 'error-message'; ?>">
            <?= htmlspecialchars($successMessage); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="program_filter">Filter by Program:</label>
        <select name="program_filter" id="program_filter" onchange="filterStudents()">
            <option value="">All Programs</option>
            <option value="STEM 1">STEM 1</option>
            <option value="STEM 2">STEM 2</option>
        </select>

        <label for="student_id">Select Student:</label>
        <select name="student_id" id="student_id" required>
            <option value="">Select a student</option>
            <?php
            // Populate the dropdown with students
            if ($studentsResult->num_rows > 0) {
                while ($row = $studentsResult->fetch_assoc()) {
                    $currentGrade = ($row['grades'] && $row['subject']) ? " [Current Grade: {$row['grades']} - {$row['subject']}]" : "";
                    echo "<option value='" . $row['student_id'] . "' data-program='" . htmlspecialchars($row['program']) . "'>" 
                        . $row['first_name'] . " " . $row['last_name'] . " (" . $row['program'] . ")" 
                        . $currentGrade . "</option>";
                }
            } else {
                echo "<option value=''>No students found</option>";
            }
            ?>
        </select>

        <label for="subject">Select Subject:</label>
        <select name="subject" id="subject" required>
            <option value="">Select a subject</option>
            <option value="Oral Comm.">Oral Communication</option>
            <option value="Gen. Math">General Mathematics</option>
            <option value="21st Century">21st Century Literature</option>
        </select>

        <label for="grade">Grade:</label>
        <input type="number" name="grade" id="grade" step="0.01" min="0" max="100" required>

        <button type="submit">Submit Grade</button>
    </form>

    <!-- Separate button for the login to dashboard -->
    <button class="cta-button" onclick="location.href='teachers_dashboard.php'">Back to Home</button>
</div>

<script>
function filterStudents() {
    const programFilter = document.getElementById('program_filter').value;
    const studentSelect = document.getElementById('student_id');
    const options = studentSelect.getElementsByTagName('option');

    for (let option of options) {
        if (option.value === "") continue; // Skip the "Select a student" option
        
        const program = option.getAttribute('data-program');
        if (programFilter === "" || program === programFilter) {
            option.style.display = "";
        } else {
            option.style.display = "none";
        }
    }
    
    // Reset student selection when filter changes
    studentSelect.value = "";
}
</script>

</body>
</html>

<?php
// Close the connection
$conn->close();
?>
