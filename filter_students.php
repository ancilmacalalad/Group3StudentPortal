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
    $search_query = $_POST['search'];
}

// SQL query to filter records by the first letter of last name
$sql = "SELECT student_id, first_name, last_name, email, contact_number, program, address, img_url, date_of_birth 
        FROM studentportal 
        WHERE last_name LIKE ? 
        AND usertype IN ('user')"; // Exclude 'admin' users
$stmt = $conn->prepare($sql);
$search_param = "$search_query%";  // Match last name starting with the input letter
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
        /* General page styling */
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
            cursor: pointer;
            transition: transform 0.3s;
        }

        img:hover {
            transform: scale(1.1);
        }

        .search-bar {
            text-align: center;
            margin-bottom: 20px;
        }

        .search-bar input {
            padding: 10px;
            font-size: 16px;
            width: 50%;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-right: 10px;
        }

        .search-bar button {
            padding: 10px 15px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-bar button:hover {
            background-color: #45a049;
        }

        p {
            text-align: center;
            color: #666;
            font-size: 18px;
        }

        .back-button {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px;
            text-align: center;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            text-decoration: none;
        }

        .back-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<h2>Student Records</h2>

<!-- Search Bar -->
<div class="search-bar">
    <form method="POST" action="">
        <input type="text" name="search" placeholder="Search by First Letter of Last Name" value="<?php echo htmlspecialchars($search_query); ?>">
        <button type="submit">Search</button>
    </form>
</div>

<div class="table-container">
    <?php
    // Check if there are results
    if ($result->num_rows > 0) {
        // Display data in a table
        echo "<table>";
        echo "<tr><th>Student ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Contact Number</th><th>Program</th><th>Address</th><th>Date of Birth</th><th>Image</th></tr>";

        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["student_id"] . "</td>";
            echo "<td>" . $row["first_name"] . "</td>";
            echo "<td>" . $row["last_name"] . "</td>";
            echo "<td>" . $row["email"] . "</td>";
            echo "<td>" . $row["contact_number"] . "</td>";
            echo "<td>" . $row["program"] . "</td>";
            echo "<td>" . $row["address"] . "</td>";

            // Format and display Date of Birth
            $dob = $row["date_of_birth"];
            echo "<td>" . date('F j, Y', strtotime($dob)) . "</td>";  // Display DOB in a readable format

            // Display image if img_url is available
            $img_url = $row["img_url"];
            if (!empty($img_url)) {
                echo "<td><a href='" . $img_url . "' target='_blank'><img src='" . $img_url . "' alt='Profile Image'></a></td>";
            } else {
                echo "<td class='no-image'>No image</td>";
            }

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

<!-- Back to Home Button -->
<a href="teachers_dashboard.php" class="back-button">Back to Home</a>

</body>
</html>
