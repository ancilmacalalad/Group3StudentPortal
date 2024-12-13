<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select User Type</title>
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

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .box {
            background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent white */
            width: 400px; /* Increased width */
            padding: 40px; /* Increased padding */
            border-radius: 15px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.4);
            text-align: center;
        }

        .title {
            font-size: 32px; /* Increased font size */
            font-weight: bold;
            margin-bottom: 30px; /* Increased margin */
            color: #333;
        }

        .button {
            width: 100%;
            padding: 15px; /* Increased padding */
            margin: 15px 0; /* Increased margin */
            font-size: 18px; /* Increased font size */
            font-weight: bold;
            color: white;
            background-color: #4CAF50;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #45a049;
        }

        .back-button {
            width: 100px;  /* Smaller width */
            padding: 10px; /* Smaller padding */
            margin: 15px auto; /* Center the button */
            font-size: 16px;
            font-weight: bold;
            color: white;
            background-color: #007bff; /* Blue color */
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="box">
            <div class="title">Select User Type</div>
            <form method="get">
                <!-- Student Button -->
                <button type="button" class="button" onclick="redirectTo('add_role.php')">Student</button>
                <!-- Teacher Button -->
                <button type="button" class="button" onclick="redirectTo('add_teacher.php')">Teacher</button>
                <!-- Back Button -->
                <button type="button" class="back-button" onclick="redirectTo('ADMIN_dashboard.php')">Back</button>
            </form>
        </div>
    </div>

    <script>
        function redirectTo(page) {
            window.location.href = page; // Redirects to the specified page
        }
    </script>
</body>
</html>