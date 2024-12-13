<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "studentportal";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_SESSION['email'];
$stmt = $conn->prepare("SELECT student_id, first_name, last_name, email, program, img_url, stats FROM studentportal WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $studentId = $row['student_id'];
    $studentName = $row['first_name'] . " " . $row['last_name'];
    $studentEmail = $row['email'];
    $studentProgram = $row['program'];
    $studentStatus = $row['stats'];
    $_SESSION['img_url'] = $row['img_url'];

}

$stmt->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <style>
        /* Global Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            width: 100%;
            overflow-x: hidden;
        }

        /* Header Styling */
        header {
            background-image: url('https://img.freepik.com/premium-photo/modern-blue-abstract-background-corporate-branding-design_1121645-6002.jpg');
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #ccc;
            position: relative;
        }

        .school-logo {
            width: 80px;
            height: auto;
            margin-right: 15px;
        }

        header h1 {
            font-size: 1.8rem;
            margin: 0;
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

        .background {
            background-image: url('https://scontent.fpag2-1.fna.fbcdn.net/v/t1.6435-9/144479337_100276648761424_1300734801011337603_n.jpg?_nc_cat=110&ccb=1-7&_nc_sid=86c6b0&_nc_eui2=AeEJntlOHIjNwx9MhwiHnDU-R1fQJgdTMN9HV9AmB1Mw3xW9WxJqEvFjvBDYnohuPLwCCUXHQbYiNOoynb9OQfG4&_nc_ohc=YlY_j8VLjgwQ7kNvgElQ_LA&_nc_zt=23&_nc_ht=scontent.fpag2-1.fna&_nc_gid=A3cn4KNCMB4fLbqskAr99RD&oh=00_AYCe_0jzh6E8dNw_RSpxDOs7LWpYi4dYMs_WeQOqZSZwVw&oe=675E61D8');
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: 60% 29%;
            opacity: 0.5;
            z-index: -1;
        }

        /* Profile Container */
        .profile-container {
            text-align: center;
            margin: 30px auto;
            background-color: rgba(255, 255, 255, 0.95);
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
        }

        .profile-details h2 {
            font-size: 1.5rem;
            margin: 10px 0;
            color: #333;
        }

        .profile-details p {
            margin: 5px 0;
            font-size: 14px;
            color: #555;
        }

        /* Advisory */
        .advisory {
            background-color: #fbeaea;
            padding: 15px;
            border-left: 5px solid #e74c3c;
            margin: 20px auto;
            max-width: 800px;
            border-radius: 5px;
            color: #c0392b;
        }

        /* Container Styling */
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
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 200px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.15);
        }

        .card span a {
            text-decoration: none;
            color: #3498db;
            font-weight: bold;
        }

        .card span a:hover {
            color: #2c3e50;
        }
        .filter-dropdown {
            position: relative;
            display: inline-block;
            margin-bottom: 20px;
        }

        .filter-button {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .filter-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 5px;
        }

        .filter-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .filter-content a:hover {
            background-color: #f1f1f1;
        }

        .show {
            display: block;
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
            background-color: #fdfdfd;
            padding: 20px;
            margin: 20px auto;
            max-width: 800px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .emergency-contact h2 {
            color: #e74c3c;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        .emergency-contact p {
            margin: 5px 0;
            font-size: 14px;
        }
      

    </style>
</head>
<body>
<header>
    <div class="background"></div>
    <div style="display: flex; align-items: center;">
        <img src="https://scontent.fmnl33-5.fna.fbcdn.net/v/t1.6435-9/156428209_111727874282968_8870282392851473995_n.jpg?_nc_cat=102&ccb=1-7&_nc_sid=6ee11a&_nc_eui2=AeEuB3FFAG2xsUkKY2dcEZ1TriMauJslouiuIxq4myWi6FhXbgaIUb8If-CpPDA696nSUDmV0-G8f_8qFmBRqR4M&_nc_ohc=HjRamtz2dvoQ7kNvgHLqhol&_nc_zt=23&_nc_ht=scontent.fmnl33-5.fna&_nc_gid=AQCW8TqOQsYQIYM2a7fI264&oh=00_AYCcjYt0984asi--YipPj6WwnVZLVDU1cK1OtC9vlIalMg&oe=673E4CBD" alt="School Logo" class="school-logo">
        <h1 style="margin-left: 10px;">San Juan Senior High School</h1>
    </div>
    <button class="btn-logout" onclick="window.location.href='logout.php'">Logout</button> 
</header>

 
    </header>

    <div class="profile-container">
    <img src="<?php echo !empty($_SESSION['img_url']) ? htmlspecialchars($_SESSION['img_url']) : 'https://via.placeholder.com/150'; ?>" alt="Student Photo">
    <div class="profile-details">
        <h2><?php echo htmlspecialchars($studentName); ?></h2>
        <p>Student ID: <?php echo htmlspecialchars($studentId); ?></p>
        <p>Email: <?php echo htmlspecialchars($studentEmail); ?></p>

    </div>
    </div>

    <div class="advisory">
        <h3>Advisory</h3>
        <p>Juanitos and Juanitas. Do not share your account passwords. Stay safe!</p>
    </div>


    <div class="container">
    <!-- Enrollment Section -->
    <h2 class="section-title">Enrollment - FIRST QUARTER SY 2024-2025</h2>
    <div class="card-grid">
        <div class="card">
            <i class="icon">📝</i>
            <span><a href="grades.php">View Grades</a></span>
        </div>
       
    
       
        <div class="card">
            <i class="icon">📄</i>
            <span><a href="update_data.php">Update Data</a></span>
        </div>
    </div>

    <!-- Combined Services and Others Section -->
    <div class="combined-services-others">
        <!-- Services Section -->
        <div class="services">
            <h2 class="section-title">Services</h2>
            <div class="card-grid">
                <div class="card">
                    <i class="icon">🧠‍🏫</i>
                    <span><a href="teacher_consultation.php">Teacher Consultation</a></span>
                </div>
                <div class="card">
                    <i class="icon">🆔</i>
                    <span><a href="view_student_id.php">View Student ID</a></span>
                </div>
            </div>
        </div>
    </div>
</div>


    <footer style="position: fixed; bottom: 0; width: 100%;">
        <p>© 2024 San Juan Senior High School | All Rights Reserved</p>
    </footer>
</body>
</html>