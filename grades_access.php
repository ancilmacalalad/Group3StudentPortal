<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'studentportal');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $grade_access = isset($_POST['grade_access']) ? 1 : 0;

    // Update grade access permission in students table
    $stmt = $conn->prepare("UPDATE studentportal SET grade_access = ? WHERE student_id = ?");
    $stmt->bind_param("ii", $grade_access, $student_id);
    if ($stmt->execute()) {
        $message = "Permissions updated successfully!";
    } else {
        $message = "Error updating permissions: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch student list with program (updated table name)
$students = [];
$result = $conn->query("SELECT student_id, CONCAT(first_name, ' ', last_name) AS full_name, program, grade_access 
                       FROM studentportal
                       ORDER BY program, full_name");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Set Grade Access</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
            max-width: 600px;
            margin: auto;
        }
        h2 { text-align: center; color: #2a9d8f; }
        .container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
        .btn, .back-btn {
            display: block;
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-align: center;
            margin-top: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn {
            background-color: #2a9d8f;
            color: white;
            border: 1px solid #238b7e;
        }
        .btn:hover {
            background-color: #238b7e;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            transform: translateY(-1px);
        }
        .back-btn {
            background-color: #90EE90;  /* Light green color */
            color: #333;  /* Darker text for better contrast */
            text-decoration: none;
            border: 1px solid #7FCD7F;
        }
        .back-btn:hover {
            background-color: #7FCD7F;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            transform: translateY(-1px);
        }
        .back-btn i {
            margin-right: 8px;
        }
        select, label {
            display: block;
            margin-bottom: 15px;
            font-size: 16px;
        }
        select { width: 100%; padding: 10px; border-radius: 4px; border: 1px solid #ccc; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Set Grade Access Permissions</h2>
        <form action="" method="POST">
            <label for="program_filter">Filter by Program:</label>
            <select name="program_filter" id="program_filter">
                <option value="">All Programs</option>
                <option value="STEM 1">STEM 1</option>
                <option value="STEM 2">STEM 2</option>
            </select>

            <label for="student_id">Select Student:</label>
            <select name="student_id" id="student_id" required>
                <option value="">Select Student</option>
                <?php foreach ($students as $student): ?>
                    <option value="<?php echo $student['student_id']; ?>" 
                            data-program="<?php echo htmlspecialchars($student['program']); ?>">
                        <?php echo htmlspecialchars($student['full_name']); ?> 
                        (<?php echo htmlspecialchars($student['program']); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
            <label>
                <input type="checkbox" name="grade_access" <?php echo $student['grade_access'] ? 'checked' : ''; ?>> Allow grade viewing
            </label>
            <button type="submit" class="btn">Save Permission</button>
        </form>
        <?php if (isset($message)): ?>
            <p><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <a href="teachers_dashboard.php" class="back-btn">
            <i>←</i> Back to Dashboard
        </a>
    </div>
    <script>
        document.getElementById('program_filter').addEventListener('change', function() {
            const selectedProgram = this.value;
            const studentSelect = document.getElementById('student_id');
            const options = studentSelect.getElementsByTagName('option');
            
            for (let option of options) {
                if (option.value === '') continue; // Skip the "Select Student" option
                
                const program = option.getAttribute('data-program');
                if (selectedProgram === '' || program === selectedProgram) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                }
            }
            
            // Reset student selection when changing program
            studentSelect.value = '';
        });
    </script>
</body>
</html>
