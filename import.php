<?php
require 'vendor/autoload.php'; // Ensure PhpSpreadsheet is included
use PhpOffice\PhpSpreadsheet\IOFactory;

session_start();

// Database connection
$host = 'localhost'; // Change this if your DB is hosted elsewhere
$user = 'root';      // DB username
$password = '';      // DB password
$dbname = 'studentportal'; // Replace with your database name

$conn = new mysqli($host, $user, $password, $dbname);

// Check for database connection errors
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Check if a file was uploaded
if (isset($_FILES['userfile']) && $_FILES['userfile']['error'] == 0) {
    $filePath = $_FILES['userfile']['tmp_name'];

    // Validate that the file is an Excel file
    $fileType = $_FILES['userfile']['type'];
    if ($fileType !== 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' && 
        $fileType !== 'application/vnd.ms-excel') {
        echo "<script>alert('Invalid file type. Please upload an Excel file.'); window.location.href='ADMIN_dashboard.php';</script>";
        exit;
    }

    try {
        // Load the uploaded Excel file
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Skip the first row (header row) if needed
        $isHeader = true;
        foreach ($rows as $row) {
            if ($isHeader) {
                $isHeader = false; // Skip header
                continue;
            }

            // Map data from each row to the database columns
            $last_name = isset($row[0]) ? $conn->real_escape_string(trim($row[0])) : null;        // Column A
            $first_name = isset($row[1]) ? $conn->real_escape_string(trim($row[1])) : null;      // Column B
            $student_id = isset($row[2]) ? $conn->real_escape_string(trim($row[2])) : null;      // Column C
            $email = isset($row[3]) ? $conn->real_escape_string(trim($row[3])) : null;           // Column D
            $date_of_birth = isset($row[4]) ? trim($row[4]) : null;   // Column E
            $contact_number = isset($row[5]) ? $conn->real_escape_string(trim($row[5])) : null;  // Column F
            $status = isset($row[6]) ? $conn->real_escape_string(trim($row[6])) : null;          // Column G
            $cp_number = isset($row[7]) ? $conn->real_escape_string(trim($row[7])) : null;       // Column H

            // Format date_of_birth to YYYY-MM-DD if valid
            if ($date_of_birth) {
                $date = DateTime::createFromFormat('m/d/Y', $date_of_birth); // Assuming the date is in MM/DD/YYYY format
                if ($date && $date->format('m/d/Y') === $date_of_birth) {
                    $date_of_birth = $date->format('Y-m-d'); // Convert to YYYY-MM-DD
                } else {
                    $date_of_birth = null; // Invalid date, set to NULL or handle as needed
                }
            }

            // Skip empty rows
            if (empty($last_name) && empty($first_name) && empty($student_id) && empty($email)) {
                continue;
            }

            // Insert into the database table
            $sql = "INSERT INTO `students` (`last_name`, `first_name`, `student_id`, `email`, `date_of_birth`, `contact_number`, `status`, `cp_number`) 
                    VALUES ('$last_name', '$first_name', '$student_id', '$email', '$date_of_birth', '$contact_number', '$status', '$cp_number')";

            if (!$conn->query($sql)) {
                echo "<script>alert('Error inserting data: " . $conn->error . "'); window.location.href='ADMIN_dashboard.php';</script>";
                exit;
            }
        }

        // Success message
        echo "<script>alert('File uploaded and data inserted successfully.'); window.location.href='ADMIN_dashboard.php';</script>";
    } catch (Exception $e) {
        echo "<script>alert('Error processing file: " . $e->getMessage() . "'); window.location.href='ADMIN_dashboard.php';</script>";
    }
} else {
    echo "<script>alert('No file selected or upload failed.'); window.location.href='ADMIN_dashboard.php';</script>";
}

// Close the database connection
$conn->close();
?>
