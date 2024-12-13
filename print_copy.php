<?php
session_start();
require_once 'includes/db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Get student information
$stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

// Get student grades
$stmt = $conn->prepare("SELECT subjects.subject_name, grades.grade, grades.semester 
                       FROM grades 
                       JOIN subjects ON grades.subject_id = subjects.subject_id 
                       WHERE grades.student_id = ?
                       ORDER BY grades.semester");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$grades = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Print Grades</title>
    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .print-btn {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <button class="print-btn no-print" onclick="window.print()">Print Grades</button>
    
    <div class="header">
        <h2>Student Grade Report</h2>
        <p>Name: <?php echo $student['first_name'] . ' ' . $student['last_name']; ?></p>
        <p>Student ID: <?php echo $student['student_id']; ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Subject</th>
                <th>Grade</th>
                <th>Semester</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($grade = $grades->fetch_assoc()): ?>
            <tr>
                <td><?php echo $grade['subject_name']; ?></td>
                <td><?php echo $grade['grade']; ?></td>
                <td><?php echo $grade['semester']; ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="no-print">
        <a href="student_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
