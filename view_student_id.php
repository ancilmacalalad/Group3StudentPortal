<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'studentportal';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_SESSION['email'];
$sql = "SELECT first_name, last_name, student_id, address, contact_number, img_url, date_of_birth FROM studentportal WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student ID Card</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: #f0f4f8;
            font-family: 'Arial', sans-serif;
            padding: 20px;
        }

        .id-cards-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 20px auto;
        }

        .id-card {
            width: 120mm; /* Increased width */
            height: 85mm; /* Increased height */
            position: relative;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin: 20px;
        }

        .id-card-header {
            background: linear-gradient(135deg, #0056b3 0%, #007bff 100%);
            color: #ffffff;
            padding: 10px;
            text-align: center;
            position: relative;
            height: 60px;
        }

        .header-content {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 10px;
            padding-left: 10px;
        }

        .school-logo {
            width: 20px; /* Reduced size for logo */
            height: auto;
            margin-right: 5px;
        }

        .header-text {
            text-align: left;
        }

        .id-card-header h2 {
            font-size: 12px;
            margin-bottom: 2px;
            font-weight: 600;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
        }

        .id-card-header h3 {
            font-size: 10px;
            font-weight: 500;
        }

        .student-photo {
            width: 80px; /* Slightly increased size for photo */
            height: 80px;
            border-radius: 50%;
            border: 3px solid #ffffff;
            position: absolute;
            top: 25px;
            right: 20px;
            object-fit: cover;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            z-index: 3;
        }

        .id-card-body {
            padding: 15px;
            margin-top: 5px;
            display: flex;
            flex-direction: column;
            gap: 4px;
            background-color: rgba(255, 255, 255, 0.9);
        }

        .id-card-body p {
            font-size: 12px; /* Slightly larger text */
            line-height: 1.3;
            color: #2c3e50;
        }

        .label {
            font-weight: bold;
            color: #0056b3;
            display: inline-block;
            width: 90px;
        }

        .id-card-footer {
            background: linear-gradient(135deg, #0056b3 0%, #007bff 100%);
            color: #ffffff;
            text-align: center;
            padding: 5px;
            position: absolute;
            bottom: 0;
            width: 100%;
            font-size: 9px;
            font-style: italic;
        }

        /* Back card styles */
        .id-card-back-content {
            padding: 15px;
            font-size: 9px;
            line-height: 1.2;
            background-color: rgba(255, 255, 255, 0.9);
        }

        .id-card-back-content h3 {
            font-size: 10px;
            color: #0056b3;
            margin: 5px 0 3px 0;
            font-weight: 600;
        }

        .id-card-back-content p {
            margin-bottom: 5px;
            color: #2c3e50;
        }

        .button-container {
            margin-top: 30px;
        }

        .btn {
            padding: 10px 20px;
            margin: 0 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .btn-print {
            background: linear-gradient(135deg, #28a745 0%, #34ce57 100%);
            color: white;
        }

        .btn-back {
            background: linear-gradient(135deg, #0056b3 0%, #007bff 100%);
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        @media print {
            .button-container {
                display: none;
            }
            body {
                margin: 0;
                padding: 0;
            }
            .id-cards-container {
                margin: 0;
            }
            .id-card {
                page-break-inside: avoid;
                margin: 0;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="id-cards-container">
        <!-- Front Card -->
        <div class="id-card">
            <div class="id-card-header">
                <div class="header-content">
                <img src="https://scontent.fmnl33-5.fna.fbcdn.net/v/t1.6435-9/156428209_111727874282968_8870282392851473995_n.jpg?_nc_cat=102&ccb=1-7&_nc_sid=6ee11a&_nc_eui2=AeEuB3FFAG2xsUkKY2dcEZ1TriMauJslouiuIxq4myWi6FhXbgaIUb8If-CpPDA696nSUDmV0-G8f_8qFmBRqR4M&_nc_ohc=HjRamtz2dvoQ7kNvgHLqhol&_nc_zt=23&_nc_ht=scontent.fmnl33-5.fna&_nc_gid=AQCW8TqOQsYQIYM2a7fI264&oh=00_AYCcjYt0984asi--YipPj6WwnVZLVDU1cK1OtC9vlIalMg&oe=673E4CBD" alt="School Logo" class="school-logo">
                    <div class="header-text">
                        <h2>San Juan Senior High School</h2>
                        <h3>Lipahan San Juan Batangas</h3>
                        <h3>361016</h3>   
                    </div>
                </div>
            </div>
            
            <?php if (!empty($row['img_url'])): ?>
                <img src="<?php echo htmlspecialchars($row['img_url']); ?>" alt="Student Photo" class="student-photo">
            <?php endif; ?>
            
            <div class="id-card-body">
                <p><span class="label">Name:</span> <?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></p>
                <p><span class="label">Student ID:</span> <?php echo htmlspecialchars($row['student_id']); ?></p>
                <p><span class="label">Address:</span> <?php echo htmlspecialchars($row['address']); ?></p>
                <p><span class="label">Birth Date:</span> <?php echo htmlspecialchars($row['date_of_birth']); ?></p>
                <p><span class="label">Contact:</span> <?php echo htmlspecialchars($row['contact_number']); ?></p>
            </div>
            
            <div class="id-card-footer">
                <p>...developing potentials for a brighter future</p>
            </div>
        </div>

        <!-- Back Card -->
        <div class="id-card">
            <div class="id-card-header">
                <div class="header-content">
                    <h2>San Juan Senior High School</h2>
                </div>
            </div>
            <div class="id-card-back-content">
                <h3>Mission</h3>
                <p>To protect and promote the right of every Filipino to quality, equitable, culture-based, and complete basic education.</p>
                
                <h3>Vision</h3>
                <p>We dream of Filipinos who passionately love their country and whose values and competencies enable them to realize their full potential and contribute meaningfully to building the nation.</p>
                <br>
                <h3>Contact Information</h3>
                <p>Email: 361016@deped.gov</p>
                <p>Email: sanjuanshs361016@gmail.com</p>
                <p>Contact: (043)-575-8577</p>
                <p>Facebook: Deped Tayo - San Juan SHS - Batangas</p>
            </div>
             <div class="id-card-footer">
                <p>...developing potentials for a brighter future</p>
            </div>
        </div>
       
    </div>

    <div class="button-container">
        <button onclick="window.print()" class="btn btn-print">Print ID Card</button>
        <button onclick="window.location.href='student_dashboard.php'" class="btn btn-back">Back to Dashboard</button>
    </div>
</body>
</html>