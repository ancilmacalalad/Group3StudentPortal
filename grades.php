<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$conn = new mysqli('localhost', 'root', '', 'studentportal');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$grades = [];
$isAllowed = false;
$studentName = '';

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    
    $stmt = $conn->prepare("SELECT * FROM studentportal WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $studentName = $row['first_name'] . ' ' . $row['last_name'];
        
        if ($row['grade_access'] == 1) {
            // Split subjects and grades by newline instead of comma
            $subjects = explode("\n", $row['subject']);
            $gradeValues = explode("\n", $row['grades']);
            
            // Combine subjects and grades
            for ($i = 0; $i < count($subjects); $i++) {
                if (isset($gradeValues[$i])) {
                    $grades[] = [
                        'subject' => trim($subjects[$i]),
                        'grade' => trim($gradeValues[$i])
                    ];
                }
            }
            $isAllowed = true;
        }
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Student Grades</title>
    <style>
        body {
            background: url('https://scontent.fmnl30-2.fna.fbcdn.net/v/t1.6435-9/144479337_100276648761424_1300734801011337603_n.jpg?_nc_cat=110&ccb=1-7&_nc_sid=86c6b0&_nc_eui2=AeEJntlOHIjNwx9MhwiHnDU-R1fQJgdTMN9HV9AmB1Mw3xW9WxJqEvFjvBDYnohuPLwCCUXHQbYiNOoynb9OQfG4&_nc_ohc=Cx8D0-h-HgcQ7kNvgHb12Je&_nc_zt=23&_nc_ht=scontent.fmnl30-2.fna&_nc_gid=AHVqySvdJUJh2_Waj9J6gS4&oh=00_AYDcxEfTxCEiuFDYE8F7qcxzPlYV2XQfkKOU0OU2XgOxWQ&oe=67393F98') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }
      

        .container {
            position: relative;
            z-index: 2;
            max-width: 800px;
            width: 90%;
            margin: 40px auto;
            padding: 40px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transform: translateY(-20px);
            animation: containerFadeIn 0.8s ease forwards;
        }

        @keyframes containerFadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Glass morphism effect */
        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 20px;
            background: linear-gradient(
                45deg,
                rgba(255, 255, 255, 0.1),
                rgba(255, 255, 255, 0.2)
            );
            z-index: -1;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .container {
                width: 85%;
                margin: 20px auto;
                padding: 30px;
            }
        }

        @media (max-width: 480px) {
            .container {
                width: 90%;
                margin: 15px auto;
                padding: 20px;
            }
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 2em;
        }

        .grades-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .grades-table th, .grades-table td {
            padding: 15px 20px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .grades-table th {
            background: linear-gradient(45deg, #4CAF50, #45a049);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 14px;
        }

        .grades-table tr:hover {
            background-color: #f8f9fa;
        }

        .grades-table td {
            font-size: 16px;
            color: #333;
        }

        .back-button {
            display: inline-block;
            padding: 12px 25px;
            background: linear-gradient(45deg, #4CAF50, #45a049);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            margin-top: 30px;
            transition: all 0.3s ease;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 14px;
            border: none;
            cursor: pointer;
        }

        .back-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.4);
        }

        .no-grades {
            text-align: center;
            color: #666;
            padding: 30px;
            background: #f8f9fa;
            border-radius: 10px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($studentName); ?></h1>

        <?php if ($isAllowed && !empty($grades)): ?>
            <table class="grades-table">
                <tr>
                    <th>Subject</th>
                    <th>Grade</th>
                </tr>
                <?php foreach ($grades as $grade): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($grade['subject']); ?></td>
                        <td><?php echo htmlspecialchars($grade['grade']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <div class="no-grades">
                <p>No grades available yet. Please check back later.</p>
            </div>
        <?php endif; ?>

        <center>
            <a href="student_dashboard.php" class="back-button">Back to Dashboard</a>
        </center>
    </div>
</body>
</html>
