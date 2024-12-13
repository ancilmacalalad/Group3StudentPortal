<?php 

error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";
$user = "root";
$password = "";
$db = "studentportal";

// Create a connection
$conn = mysqli_connect($host, $user, $password, $db);
if ($conn === false) {
    die("Connection error: " . mysqli_connect_error());
}

session_start(); // Start the session

$error_message = ""; // Initialize an empty error message
$login_successful = false; // Track login success status

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["passcode"]; // Assuming you verify this password

    // Prepare SQL query to prevent SQL injection
    $stmt = $conn->prepare("SELECT first_name, last_name, usertype FROM studentportal WHERE email = ? AND passcode = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are any results
    if ($result->num_rows > 0) {
        // User credentials are correct
        while ($row = $result->fetch_assoc()) {
            // Store full name and user type in session
            $_SESSION['student_name'] = $row['first_name'] . " " . $row['last_name'];
            $_SESSION['email'] = $email; // Store email in session if needed
            $_SESSION['user_type'] = $row['usertype']; // Store user type in session
           
        }
        
        $login_successful = true; // Set login success to true
        
        // Redirect to dashboard after a delay (handled in JavaScript)
    } else {
        // Display error message if no user is found
        $error_message = "Email and Password are Incorrect.";
    }
    
    $stmt->close();
}

$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <style>
       .background {
            font-family: Arial, sans-serif;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('https://scontent.fmnl30-2.fna.fbcdn.net/v/t1.6435-9/144479337_100276648761424_1300734801011337603_n.jpg?_nc_cat=110&ccb=1-7&_nc_sid=86c6b0&_nc_eui2=AeEJntlOHIjNwx9MhwiHnDU-R1fQJgdTMN9HV9AmB1Mw3xW9WxJqEvFjvBDYnohuPLwCCUXHQbYiNOoynb9OQfG4&_nc_ohc=Cx8D0-h-HgcQ7kNvgHb12Je&_nc_zt=23&_nc_ht=scontent.fmnl30-2.fna&_nc_gid=AHVqySvdJUJh2_Waj9J6gS4&oh=00_AYDcxEfTxCEiuFDYE8F7qcxzPlYV2XQfkKOU0OU2XgOxWQ&oe=67393F98') no-repeat center center fixed; 
            background-size: cover;
            margin: 0;
            padding: 0;
            background-position: center;
            z-index: -1; 
            opacity: 0.7;
        }

        .login-form {
            background-color: whitesmoke;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 100%;
            text-align: center;
            margin: auto;
            margin-top: 250px;
        }

        .login-form h2 {
            margin-bottom: 30px;
            color: #333;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }

        input:focus {
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
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        .forgot-password {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }

        .forgot-password a {
            text-decoration: none;
            color: #007BFF;
            font-weight: bold;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        .error-message {
    display: none; /* Hide error message initially */
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
    padding: 10px;
    border-radius: 5px;
    margin-top: 20px;
    font-weight: bold;
}


        .loading-circle {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
            display: none; /* Hidden by default */
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 600px) {
            .login-form {
                padding: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="background"></div>
    <div class="login-form">
        <h2>Login</h2>
        <form action="" method="POST" onsubmit="showLoading();">
            <label for="email">Email:</label>
            <input type="text" id="email" name="email" placeholder="Enter email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="passcode" placeholder="Enter password" required>

            <button id="login-button" type="submit">Log In</button>
            <div id="loading-circle" class="loading-circle"></div> <!-- Loading circle -->
        </form>

        <div class="forgot-password">
            <a href="forgot_password.php">Forgot Password?</a>
        </div>

        <?php if (!empty($error_message)) { ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php } ?>
    </div>

    <script>
// Check if login was successful, and if so, show loading effect for redirection
<?php if ($login_successful) { ?>
    // Show loading circle and redirect after a delay
    document.getElementById('loading-circle').style.display = 'block'; // Show loading circle
    setTimeout(function() {
        // Redirect based on user type
        var userType = <?php echo json_encode($_SESSION['user_type']); ?>;
        if (userType === 'admin') {
            window.location.href = 'http://localhost/PORTAL/ADMIN_dashboard.php'; // Admin dashboard
        } else if (userType === 'teacher') { // Check for teacher user type
            window.location.href = 'http://localhost/PORTAL/teachers_dashboard.php'; // Teacher dashboard
        } else {
            window.location.href = 'http://localhost/PORTAL/student_dashboard.php'; // Student dashboard
        }
    }, 3000); // 3-second delay
<?php } else if (!empty($error_message)) { ?>
    // If there was an error, show loading circle temporarily
    document.getElementById('loading-circle').style.display = 'block'; // Show loading circle
    setTimeout(function() {
        document.getElementById('loading-circle').style.display = 'none'; // Hide loading circle
        // Show error message after hiding loading circle
        document.querySelector('.error-message').style.display = 'block'; 
    }, 3000); // 3-second delay
<?php } ?>
</script>

</body>
</html>
