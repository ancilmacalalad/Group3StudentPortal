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

// Handle search
$search_query = '';
if (isset($_POST['search'])) {
    $search_query = trim($_POST['search']); // Sanitize input
}

// Query to select students by program
$sql = "SELECT student_id, first_name, last_name, email, contact_number, program, address, img_url, date_of_birth, passcode 
        FROM studentportal 
        WHERE program LIKE ? AND usertype = 'user'";
$stmt = $conn->prepare($sql);
$search_param = "%$search_query%";
$stmt->bind_param("s", $search_param);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Records</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            margin: 20px 0;
            font-size: 26px;
            color: #333;
        }

        .table-container {
            width: 90%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 16px;
            color: #333;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }

        .search-bar {
            text-align: center;
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .search-bar input {
            padding: 10px;
            font-size: 16px;
            width: 400px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .search-bar button, .back-box {
            padding: 10px 15px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            margin: 0 5px;
        }

        .back-box {
            font-size: 14px;
            padding: 8px 12px;
        }

        p {
            text-align: center;
            color: #666;
            font-size: 18px;
        }

        /* Update back button styles */
        .back-button {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }

        .back-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<h2>Student Records</h2>

<!-- Updated Search Bar with Back Box -->
<div class="search-bar">
    <form method="POST" action="" style="display: flex; align-items: center;">
        <input type="text" name="search" placeholder="Search by Course (e.g., STEM 1)" value="<?php echo htmlspecialchars($search_query); ?>">
        <button type="submit">Search</button>
    </form>
    <a href="ADMIN_dashboard.php" class="back-box">Back</a>
</div>

<div class="table-container">
    <?php
    // Check if there are results
    if ($result->num_rows > 0) {
        // Display data in a table
        echo "<table>";
        echo "<tr><th>Student ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Contact Number</th><th>Program</th><th>Address</th><th>Date of Birth</th><th>Image</th><th>Account Status</th></tr>";

        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["student_id"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["first_name"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["last_name"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["contact_number"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["program"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["address"]) . "</td>";

            // Format and display Date of Birth
            $dob = $row["date_of_birth"];
            echo "<td>" . date('F j, Y', strtotime($dob)) . "</td>";

            // Display image if img_url is available
            $img_url = $row["img_url"];
            if (!empty($img_url)) {
                echo "<td><img src='" . htmlspecialchars($img_url) . "' alt='Profile Image'></td>";
            } else {
                echo "<td>No image</td>";
            }

            // Check account status
            $account_status = empty($row["passcode"]) ? "No Account" : "Account Exists";
            echo "<td>" . htmlspecialchars($account_status) . "</td>";

            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No records found</p>";
    }

    // Close connection
    $conn->close();
    ?>
</div>

</body>
</html>
