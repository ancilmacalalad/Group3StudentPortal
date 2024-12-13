<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rank Students</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 600px; margin: auto; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 8px; border: 1px solid #ddd; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Rank Students</h2>
        <table>
            <tr>
                <th>Rank</th>
                <th>Student Name</th>
                <th>Average Grade</th>
            </tr>
            <?php
            // Database connection settings
            $host = 'localhost';
            $user = 'root';
            $password = '';
            $database = 'studentportal';  // Make sure to use your actual database name

            // Create connection
            $conn = new mysqli($host, $user, $password, $database);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Query to retrieve students and their average grades
            $sql = "SELECT students.first_name, students.last_name, AVG(grades.grade) AS average_grade
                    FROM students
                    JOIN grades ON students.student_id = grades.student_id
                    GROUP BY students.student_id
                    ORDER BY average_grade DESC";  // Order by average grade in descending order

            // Run the query
            $result = $conn->query($sql);

            // Initialize rank
            $rank = 1;

            if ($result->num_rows > 0) {
                // Output the rows of ranked students
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>{$rank}</td><td>{$row['first_name']} {$row['last_name']}</td><td>" . number_format($row['average_grade'], 2) . "</td></tr>";
                    $rank++;
                }
            } else {
                echo "<tr><td colspan='3'>No data available</td></tr>";
            }

            // Close the database connection
            $conn->close();
            ?>
        </table>
    </div>
</body>
</html>
