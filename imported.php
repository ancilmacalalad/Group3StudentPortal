<?php
// Database connection
$conn = mysqli_connect("localhost", "root", "", "studentportal");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize the query
$query = "SELECT * FROM students WHERE id >= 51";

// Check if search is submitted
if(isset($_POST['search'])) {
    $searchTerm = mysqli_real_escape_string($conn, $_POST['surname']);
    $query .= " AND last_name LIKE '%$searchTerm%'";
}

$query .= " ORDER BY id ASC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Students List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 95%;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
            text-align: center;
            font-size: 24px;
        }
        h2 {
            color: #333;
            margin: 20px 0;
            text-align: center;
            font-size: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 14px;
            table-layout: fixed;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        th {
            background-color: #2ecc71;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .back-btn {
            display: inline-block;
            padding: 8px 16px;
            background-color: #2ecc71;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .back-btn:hover {
            background-color: #27ae60;
        }
        .search-container {
            text-align: left;
            margin-bottom: 10px;
        }
        .search-input {
            padding: 8px;
            width: 800px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 5px;
        }
        .search-btn {
            padding: 8px 16px;
            background-color: #2ecc71;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-btn:hover {
            background-color: #27ae60;
        }
        /* Column widths */
        th:nth-child(1), td:nth-child(1) { width: 5%; }
        th:nth-child(2), td:nth-child(2) { width: 12%; }
        th:nth-child(3), td:nth-child(3) { width: 12%; }
        th:nth-child(4), td:nth-child(4) { width: 12%; }
        th:nth-child(5), td:nth-child(5) { width: 15%; }
        th:nth-child(6), td:nth-child(6) { width: 10%; }
        th:nth-child(7), td:nth-child(7) { width: 12%; }
        th:nth-child(8), td:nth-child(8) { width: 12%; }
        th:nth-child(9), td:nth-child(9) { width: 10%; }
    </style>
</head>
<body>
    <div class="container">
        <a href="ADMIN_dashboard.php" class="back-btn">Back to Dashboard</a>
        <h1>Imported Files</h1>
        <h2>Imported Student Records</h2>
        
        <!-- Search Form -->
        <form method="POST" class="search-container">
            <input type="text" name="surname" placeholder="Search by surname..." class="search-input" value="<?php echo isset($_POST['surname']) ? $_POST['surname'] : ''; ?>">
            <button type="submit" name="search" class="search-btn">Search</button>
            <?php if(isset($_POST['search'])): ?>
                <a href="imported.php" class="back-btn" style="margin-left: 5px;">Show All</a>
            <?php endif; ?>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student ID</th>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>Email</th>
                    <th>Date of Birth</th>
                    <th>Contact Number</th>
                    <th>CP Number</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td style='white-space: nowrap;'>" . $row['student_id'] . "</td>";
                        echo "<td>" . $row['last_name'] . "</td>";
                        echo "<td>" . $row['first_name'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . $row['date_of_birth'] . "</td>";
                        echo "<td>" . $row['contact_number'] . "</td>";
                        echo "<td>" . $row['cp_number'] . "</td>";
                        echo "<td>" . $row['status'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9' style='text-align: center;'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
mysqli_close($conn);
?>
