<?php
session_start();
$host = "localhost";
$user = "root";
$password = "";
$db = "studentportal";

// Connect to the database
$data = mysqli_connect($host, $user, $password, $db);
if ($data === false) {
    die("Connection error: " . mysqli_connect_error());
}

$appointmentDetails = '';
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['student_name'];
    $email = $_POST['email'];
    
    // Check if email exists in the studentportal table
    $email_check_query = "SELECT * FROM studentportal WHERE email = '$email'";
    $result = mysqli_query($data, $email_check_query);
    
    if (mysqli_num_rows($result) == 0) {
        $error_message = "Please use your registered student email address";
    } else {
        $date = $_POST['appointment_date'];
        
        // Continue with the rest of your validation and insertion code
        if (strtotime($date) < strtotime(date('Y-m-d'))) {
            $error_message = "Please select a future date for your appointment";
        } else {
            $time = $_POST['appointment_time'];
            $message = $_POST['notes'];

            $query = "INSERT INTO counseling_appointments (name, email, date, time, message) VALUES ('$name', '$email', '$date', '$time', '$message')";
            
            if (mysqli_query($data, $query)) {
                $appointmentDetails = "
                    <div class='success-container'>
                        <h2>Appointment Successfully Scheduled!</h2>
                        <div class='appointment-details'>
                            <p><strong>Name:</strong> $name</p>
                            <p><strong>Email:</strong> $email</p>
                            <p><strong>Date:</strong> $date</p>
                            <p><strong>Time:</strong> $time</p>
                            <p><strong>Message:</strong> $message</p>
                        </div>
                        <a href='teacher_consultation.php' class='back-button'>Schedule Another Appointment</a>
                    </div>
                ";
            } else {
                $error_message = "There was an error scheduling your appointment.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Consultation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            overflow: hidden; /* Prevent scrolling */
        }

        .background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('https://scontent.fmnl30-2.fna.fbcdn.net/v/t1.6435-9/144479337_100276648761424_1300734801011337603_n.jpg?_nc_cat=110&ccb=1-7&_nc_sid=86c6b0&_nc_eui2=AeEJntlOHIjNwx9MhwiHnDU-R1fQJgdTMN9HV9AmB1Mw3xW9WxJqEvFjvBDYnohuPLwCCUXHQbYiNOoynb9OQfG4&_nc_ohc=Cx8D0-h-HgcQ7kNvgHb12Je&_nc_zt=23&_nc_ht=scontent.fmnl30-2.fna&_nc_gid=AHVqySvdJUJh2_Waj9J6gS4&oh=00_AYDcxEfTxCEiuFDYE8F7qcxzPlYV2XQfkKOU0OU2XgOxWQ&oe=67393F98') no-repeat center center fixed;
            background-size: cover;
            opacity: 0.7;
            z-index: -1;
        }

        .form-container {
            background-color: rgba(255, 255, 255, 0.9); /* Slightly transparent white */
            padding: 40px;
            border-radius: 10px;
            border: 2px solid #4CAF50;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            width: 100%;
            margin: auto;
            margin-top: 150px;
            text-align: center;
            position: relative; /* Ensure this is above the blurred background */
            z-index: 1; /* Make sure it appears above the background */
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        input[type="text"],
        input[type="email"],
        input[type="date"],
        input[type="time"],
        textarea {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }

        input:focus, textarea:focus {
            border-color: #4CAF50;
            outline: none;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }

        .loading-circle {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
            display: none;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .error-message {
            color: red;
            margin-top: 10px;
            text-align: center;
            font-size: 14px;
        }

        .success-container {
            text-align: center;
            padding: 20px;
        }

        .appointment-details {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: left;
        }

        .back-button {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 20px;
        }

        .back-button:hover {
            background-color: #45a049;
        }

        .back-to-dashboard {
            position: fixed;
            bottom: 250px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            transition: background-color 0.3s;
        }

        .back-to-dashboard:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="background"></div>

    <div class="form-container">
        <?php if (!empty($appointmentDetails)) { ?>
            <?php echo $appointmentDetails; ?>
        <?php } else { ?>
            <h2>Schedule Consultation</h2>
            <form method="POST">
                <input type="text" name="student_name" placeholder="Your Name" required>
                <input type="email" name="email" placeholder="Enter your registered student email" required>
                <input type="date" name="appointment_date" min="<?php echo date('Y-m-d'); ?>" required>
                <input type="time" name="appointment_time" required>
                <textarea name="notes" placeholder="Additional Notes (optional)" rows="4"></textarea>

                <button type="submit">Schedule Appointment</button>
                <div class="loading-circle" id="loading-circle"></div>
            </form>
            <?php if (!empty($error_message)) { ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php } ?>
        <?php } ?>
    </div>

    <script>
        // Show loading circle on form submission
        document.querySelector("form").addEventListener("submit", function() {
            document.getElementById('loading-circle').style.display = 'block';
        });

        // Add client-side date validation
        const dateInput = document.querySelector('input[type="date"]');
        
        // Set minimum date to today
        dateInput.min = new Date().toISOString().split('T')[0];
        
        // Prevent past dates from being selected
        dateInput.addEventListener('input', function() {
            const selectedDate = new Date(this.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (selectedDate < today) {
                alert('Please select a future date');
                this.value = '';
            }
        });
    </script>

    <a href="student_dashboard.php" class="back-to-dashboard">Back to Dashboard</a>
</body>
</html>
