<?php

// Add this at the top of your file for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Establish a database connection (make sure to replace with your actual connection details)
$conn = new mysqli("localhost", "root", "", "studentportal");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Sample dynamic data for admin (replace with database queries as needed)
$email = $_SESSION['email'];
$stmt = $conn->prepare("SELECT first_name, last_name, img_url FROM studentportal WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $instructorName = $row['first_name'] . " " . $row['last_name'];
    $_SESSION['img_url'] = $row['img_url'];
}
$stmt->close();

$currentProgram = "STEM Coordinator";
$adminRole = "Admin";

// Sample emergency contact numbers (from a database if required)
$emergencyContacts = [
    "Office of Student Affairs" => "123-456-7890",
    "Health Services" => "098-765-4321",
];

$adminServices = [
    "Student Management" => "manage_students.php",
    "View Attendance" => "attendance.php",
];
?>


    <script>
        // Dropdown Filter Functionality
        const filterButton = document.querySelector('.filter-button');
        const filterContent = document.querySelector('.filter-content');

        filterButton.addEventListener('click', () => {
            filterContent.style.display = filterContent.style.display === 'block' ? 'none' : 'block';
        });

        window.onclick = function(event) {
            if (!event.target.matches('.filter-button')) {
                filterContent.style.display = 'none';
            }
        };
    </script>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Global Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f3f3;
            margin: 0;
            padding: 0;
        }

        /* Header Styling */
        header {
            background-image: url('https://img.freepik.com/premium-photo/modern-blue-abstract-background-corporate-branding-design_1121645-6002.jpg');
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #ccc;
            position: relative;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .school-logo {
            width: 80px;
            height: auto;
        }

        header h1 {
            font-size: 1.5rem;
            margin: 0;
            line-height: 1.2;
        }

        .btn-logout {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            background-color: #c0392b;
            transform: scale(1.05);
        }

        /* Profile Section */
        .profile-container {
            text-align: center;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            max-width: 600px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .profile-container img {
            border-radius: 50%;
            width: 120px;
            height: 120px;
            border: 3px solid #3498db;
            object-fit: cover;
        }

        .profile-details h2 {
            font-size: 1.4rem;
            margin: 10px 0;
            color: #2c3e50;
        }

        .profile-details p {
            margin: 5px 0;
            font-size: 14px;
            color: #555;
        }

        /* Advisory Section */
        .advisory {
            background-color: #fcf2f2;
            padding: 15px;
            border-left: 5px solid #e74c3c;
            margin: 20px auto;
            max-width: 800px;
            border-radius: 5px;
            color: #c0392b;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .advisory h3 {
            margin: 0 0 10px;
            font-size: 1.2rem;
            color: #e74c3c;
        }

        /* Card Grid Section */
        .container {
            width: 90%;
            margin: 20px auto;
        }

        .section-title {
            color: #2980b9;
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 15px;
            text-align: center;
        }

        .card-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .card {
            background-color: white;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            width: 220px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .card i {
            font-size: 2rem;
            color: #3498db;
            margin-bottom: 10px;
        }

        .card span a {
            text-decoration: none;
            color: #3498db;
            font-weight: bold;
            font-size: 14px;
        }

        .card span a:hover {
            color: #2c3e50;
        }


        /* Emergency Contact Section */
        .emergency-contact {
            background-color: #f9f9f9;
            padding: 20px;
            margin: 20px auto;
            max-width: 800px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            border-left: 5px solid #e74c3c;
        }

        .emergency-contact h2 {
            color: #e74c3c;
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .emergency-contact p {
            font-size: 14px;
            color: #555;
        }

        /* Footer Section */
        footer {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 10px 0;
            font-size: 14px;
        }

        footer p {
            margin: 0;
        }
    </style>
</head>
<body>
<header>
    <div style="display: flex; align-items: center;">
    <img src="https://scontent.fpag2-1.fna.fbcdn.net/v/t1.6435-9/156428209_111727874282968_8870282392851473995_n.jpg?_nc_cat=102&ccb=1-7&_nc_sid=6ee11a&_nc_ohc=b_hmf4b40DMQ7kNvgEgQ6tf&_nc_zt=23&_nc_ht=scontent.fpag2-1.fna&_nc_gid=AC6p2HP96fGRxgmEqlUrvMV&oh=00_AYAMTv-zL1W4n-obLf9BjPt5U1wJqmA68wGsdXJ_tZkezw&oe=674DAE3D" alt="School Logo" class="school-logo">
        <h1>San Juan Senior High School - Admin Panel</h1>
    </div>
    <button class="btn-logout" onclick="window.location.href='logout.php'">Logout</button>
</header>

<div class="profile-container">
    <img src="<?php echo !empty($_SESSION['img_url']) ? htmlspecialchars($_SESSION['img_url']) : 'https://via.placeholder.com/150'; ?>" alt="Instructor Photo">
    <h2><?php echo isset($_SESSION['student_name']) ? $_SESSION['student_name'] : ''; ?></h2>
    <p>STEM Coordinator</p>
    <p>ADMIN</p>
</div>

<div class="advisory">
    <h3>Advisory</h3>
    <p>Admins, please ensure all records are up to date. Remember, confidentiality is essential for student data management.</p>
</div>

<div class="container">
    <h2 class="section-title">Admin Services</h2>
    <div class="card-grid">
        <div class="card">
            <i class="fas fa-users"></i>
            <span><a href="manage_students.php">Manage Records</a></span>
<br>
<br>
<br>
<br>          
            <span><a href="manage_registration.php">Manage Registrations</a></span><i class="fas fa-users"></i>
        </div>
        <div class="card">
            <i class="fas fa-user-plus"></i>
            <span><a href="add_roles.php">Add Account</a></span>
        </div>
        <div class="card">
    <i class="fas fa-file-import"></i>
    <h3>Import | Export</h3>
    <form action="import.php" method="post" enctype="multipart/form-data" style="display: flex; flex-direction: column; align-items: center; gap: 10px; margin-top: 10px;">
        <input type="file" name="userfile" accept=".xlsx, .xls" required style="padding: 5px; border: 1px solid #ddd; border-radius: 5px; width: 90%; cursor: pointer;">
        <button type="submit" style="background-color: #3498db; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 14px; transition: background-color 0.3s ease;">Import Users</button>
    </form>
    <a href="imported.php" style="background-color: #2ecc71; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block;">Imported Files</a>
</div>
</div>


</div>

<div class="emergency-contact">
    <h2>Emergency Contact</h2>
    <p><strong>Health Services:</strong> 098-765-4321</p>
</div>

<footer>
    <p>&copy; 2024 San Juan Senior High School - All Rights Reserved</p>
</footer>
</body>
</html>


