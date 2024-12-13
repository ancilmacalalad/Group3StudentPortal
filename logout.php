<?php
session_start();

// Store the referring page in session when the page loads
if (!isset($_SESSION['logout_referrer']) && isset($_SERVER['HTTP_REFERER'])) {
    $_SESSION['logout_referrer'] = $_SERVER['HTTP_REFERER'];
}

// If the user clicks 'Yes', destroy the session and redirect to the login page
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}

// If the user clicks 'No', redirect back to the previous page
if (isset($_POST['cancel'])) {
    $redirect = 'student_dashboard.php'; // Default redirect

    if (isset($_SESSION['logout_referrer'])) {
        if (strpos($_SESSION['logout_referrer'], 'ADMIN_dashboard.php') !== false) {
            $redirect = 'ADMIN_dashboard.php';
        } elseif (strpos($_SESSION['logout_referrer'], 'teachers_dashboard.php') !== false) {
            $redirect = 'teachers_dashboard.php';
        }
    }
    
    header("Location: " . $redirect);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout Confirmation</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            width: 100%;
            position: relative;
        }

        .background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('https://scontent.fmnl30-2.fna.fbcdn.net/v/t1.6435-9/144479337_100276648761424_1300734801011337603_n.jpg?_nc_cat=110&ccb=1-7&_nc_sid=86c6b0&_nc_eui2=AeEJntlOHIjNwx9MhwiHnDU-R1fQJgdTMN9HV9AmB1Mw3xW9WxJqEvFjvBDYnohuPLwCCUXHQbYiNOoynb9OQfG4&_nc_ohc=Cx8D0-h-HgcQ7kNvgHb12Je&_nc_zt=23&_nc_ht=scontent.fmnl30-2.fna&_nc_gid=AHVqySvdJUJh2_Waj9J6gS4&oh=00_AYDcxEfTxCEiuFDYE8F7qcxzPlYV2XQfkKOU0OU2XgOxWQ&oe=67393F98');
            background-size: cover;
            background-position: center;
            opacity: 0.7;
            z-index: -1;
        }

        .modal-content {
            width: fit-content;
            background: rgba(255, 255, 255, 0.8);
            padding: 50px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .modal-content h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .button-container {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .modal-content button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .yes-btn {
            background-color: blue;
            color: white;
        }

        .no-btn {
            background-color: #e74c3c;
            color: white;
        }

        .yes-btn:hover {
            background-color: #45a049;
        }

        .no-btn:hover {
            background-color: #c0392b;
        }

        /* Loading bar styles */
        #loading-bar {
            width: 0;
            height: 5px;
            background-color: #3498db;
            transition: width 5s;
            display: none; /* Hidden by default */
        }

        .loading-container {
            width: 100%;
            background-color: #e0e0e0;
            height: 5px;
            margin-top: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<!-- Background -->
<div class="background"></div>

<!-- Modal Structure -->
<div class="modal-content">
    <h2>Are you sure you want to log out?</h2>
    
    <!-- Form to handle the button actions using POST -->
    <form method="POST" onsubmit="showLoading();">
        <div class="button-container">
            <button type="submit" name="logout" class="yes-btn">Yes</button>
            <button type="submit" name="cancel" class="no-btn">No</button>
        </div>
        <div class="loading-container">
            <div id="loading-bar"></div> <!-- Loading bar -->
        </div>
    </form>
</div>

<script>
    function showLoading() {
        // Show loading bar and animate it
        const loadingBar = document.getElementById('loading-bar');
        loadingBar.style.display = 'block'; // Show the loading bar
        loadingBar.style.width = '100%'; // Animate the loading bar width to 100%
    }
</script>

</body>
</html>
