<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: user_login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "studentportal");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get teacher data from database
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

// Sample data
$currentSemester = "First Semester AY 2024-2025";
$status = "Active";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Global Styling */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            width: 100%;
            overflow-x: hidden;
        }

        /* Header Styling */
        header {
            background-image: url('https://img.freepik.com/premium-photo/modern-blue-abstract-background-corporate-branding-design_1121645-6002.jpg');
            color: white;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #ccc;
            position: relative;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .school-logo {
            width: 80px;
            height: auto;
            margin-right: 15px;
        }

        header h1 {
            font-size: 2rem;
            margin: 0;
            font-weight: 600;
        }

        .btn-logout {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .btn-logout:hover {
            background-color: #c0392b;
        }

        /* Profile Section */
        .profile-container {
            text-align: center;
            margin: 30px auto;
            background-color: rgba(255, 255, 255, 0.95);
            padding: 30px;
            max-width: 700px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .profile-container img {
            border-radius: 50%;
            width: 150px;
            height: 150px;
            border: 5px solid #3498db;
            margin-bottom: 15px;
        }

        .profile-container h2 {
            font-size: 1.8rem;
            margin-bottom: 5px;
            color: #333;
        }

        .profile-container p {
            font-size: 1rem;
            margin: 5px 0;
            color: #666;
        }

        /* Instructor Services Section */
        .container {
            width: 90%;
            margin: 20px auto;
        }

        .section-title {
            color: #2980b9;
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .card-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 25px;
            justify-content: center;
        }

        .card {
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            width: 230px;
            text-align: center;
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
        }

        .card i {
            font-size: 2.5rem;
            color: #3498db;
            margin-bottom: 15px;
        }

        .card a {
            text-decoration: none;
            color: #3498db;
            font-weight: 500;
            font-size: 1.1rem;
        }

        .card a:hover {
            color: #2c3e50;
        }

        /* Footer */
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        footer p {
            margin: 0;
            font-size: 0.9rem;
        }

        /* Emergency Contact */
        .emergency-contact {
            background-color: #fff;
            padding: 20px;
            margin: 20px auto;
            max-width: 800px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .emergency-contact h2 {
            color: #e74c3c;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        .emergency-contact p {
            margin: 5px 0;
            font-size: 14px;
            color: #555;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
    <div class="background"></div>
    <div style="display: flex; align-items: center;">
        <img src="https://scontent.fpag2-1.fna.fbcdn.net/v/t1.6435-9/156428209_111727874282968_8870282392851473995_n.jpg?_nc_cat=102&ccb=1-7&_nc_sid=6ee11a&_nc_ohc=b_hmf4b40DMQ7kNvgEgQ6tf&_nc_zt=23&_nc_ht=scontent.fpag2-1.fna&_nc_gid=AC6p2HP96fGRxgmEqlUrvMV&oh=00_AYAMTv-zL1W4n-obLf9BjPt5U1wJqmA68wGsdXJ_tZkezw&oe=674DAE3D" alt="School Logo" class="school-logo">
        <h1 style="margin-left: 10px;">San Juan Senior High School - Teacher Panel</h1>
    </div>
        <button class="btn-logout" onclick="window.location.href='logout.php'">Logout</button>
    </header>

    <!-- Profile Section -->
    <div class="profile-container">
        <img src="<?php echo !empty($_SESSION['img_url']) ? htmlspecialchars($_SESSION['img_url']) : 'https://via.placeholder.com/150'; ?>" alt="Instructor Photo">
        <h2><?php echo $instructorName; ?></h2>
        <p><?php echo $currentSemester; ?></p>
        <p>Status: <?php echo $status; ?></p>
    </div>

    <!-- Instructor Services Section -->
    <div class="container">
        <h2>Instructor Services</h2>
        <div class="card-grid">
            <?php 
            $instructorServices = [
                "Filter Accounts" => ["link" => "filter_students.php", "icon" => "fa-filter"],
                "Add Grades" => ["link" => "add_grades.php", "icon" => "fa-pencil-alt"],
                "Set Grade Access Permissions" => ["link" => "grades_access.php", "icon" => "fa-lock"],
                "View Teacher's ID" => ["link" => "view_teacher_id.php", "icon" => "fa-id-card"],
                "Consultant" => ["link" => "consultant.php", "icon" => "fa-user-tie"]
            ];

            foreach ($instructorServices as $service => $details): ?>
                <div class="card">
                    <i class="fas <?php echo $details['icon']; ?>"></i>
                    <a href="<?php echo $details['link']; ?>"><?php echo $service; ?></a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Announcements Section -->
    <div class="container">
        <h2>Announcements</h2>
        <ul>
            <?php if (!empty($announcements)) : ?>
                <?php foreach ($announcements as $announcement): ?>
                    <li><?php echo $announcement; ?></li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>No announcements available.</li>
            <?php endif; ?>
        </ul>
    </div>

    <!-- Footer -->
    <footer>
        <p>© 2024 San Juan Senior High School | All Rights Reserved</p>
    </footer>
</body>
</html>
                     