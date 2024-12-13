<?php
// Database connection
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'studentportal';

$conn = new mysqli($host, $user, $password, $database);

// Check if connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if (isset($_POST['add_user'])) {
    // First validate that all required fields exist
    $required_fields = ['first_name', 'last_name', 'student_id', 'email', 
                       'contact_number', 'address', 'date_of_birth', 
                       'password', 'confirm_password', 'program'];
    
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            echo "Error: {$field} is required";
            exit;
        }
    }

    // Then collect form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact_number'];
    $address = $_POST['address'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $date_of_birth = $_POST['date_of_birth'];
    $user_type = 'user';
    $student_id = $_POST['student_id'];
    $program = $_POST['program'];
    

    // Validate student ID format (12 digits only)
    if (!preg_match('/^[0-9]{12}$/', $_POST['student_id'])) {
        echo '<div style="
            background-color: #f44336;
            color: white;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            margin: 20px auto;
            width: 80%;
            max-width: 500px;
            font-family: Arial, sans-serif;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        ">
            <h3 style="margin: 0;">Error!</h3>
            <p style="margin: 10px 0 0 0;">Please enter 12 digit number for Student ID.</p>
        </div>';
        exit;
    }

    // Validate if passwords match
    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
        exit;
    }

    // Profile Picture Handling
    $img_url = null; // Changed variable name from profile_picture to img_url
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        // Specify upload folder
        $upload_dir = 'uploads/';
        $file_name = $_FILES['profile_picture']['name'];
        $file_tmp = $_FILES['profile_picture']['tmp_name'];
        $file_path = $upload_dir . basename($file_name);

        // Check if file can be moved
        if (move_uploaded_file($file_tmp, $file_path)) {
            $img_url = $file_path; // Changed from profile_picture to img_url
        } else {
            echo '<div style="
                background-color: #f44336;
                color: white;
                padding: 20px;
                border-radius: 5px;
                text-align: center;
                margin: 20px auto;
                width: 80%;
                max-width: 500px;
                font-family: Arial, sans-serif;
                box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            ">
                <h3 style="margin: 0;">Error!</h3>
                <p style="margin: 10px 0 0 0;">Error uploading file.</p>
            </div>';
            exit;
        }
    }

    // Insert new user into database
    $sql = "INSERT INTO studentportal (first_name, last_name, email, contact_number, address, passcode, usertype, date_of_birth, img_url, student_id, program) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare and bind SQL statement
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssssssssss", $first_name, $last_name, $email, $contact_number, $address, $password, $user_type, $date_of_birth, $img_url, $student_id, $program);
        
        // Execute the statement
        if ($stmt->execute()) {
            echo '
            <div style="
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.7);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 1000;
            ">
                <div style="
                    background-color: #fff9c4;
                    padding: 30px;
                    border-radius: 10px;
                    text-align: center;
                    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                    max-width: 400px;
                    width: 90%;
                    border: 2px solid #ffd700;
                ">
                    <h2 style="color: #4CAF50; margin-bottom: 20px;">ACCOUNT IS CREATED SUCCESSFULLY</h2>
                    <a href="admin_dashboard.php" style="
                        display: inline-block;
                        background-color: #1e90ff;
                        color: white;
                        padding: 10px 20px;
                        text-decoration: none;
                        border-radius: 5px;
                        font-family: Arial, sans-serif;
                    ">Back to Dashboard</a>
                </div>
            </div>';
            exit; 
        } else {
            echo '<div style="
                background-color: #f44336;
                color: white;
                padding: 20px;
                border-radius: 5px;
                text-align: center;
                margin: 20px auto;
                width: 80%;
                max-width: 500px;
                font-family: Arial, sans-serif;
                box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            ">
                <h3 style="margin: 0;">Error!</h3>
                <p style="margin: 10px 0 0 0;">' . $stmt->error . '</p>
            </div>';
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Student Account</title>
    <style>
        body {
            .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('https://scontent.fmnl30-2.fna.fbcdn.net/v/t1.6435-9/144479337_100276648761424_1300734801011337603_n.jpg?_nc_cat=110&ccb=1-7&_nc_sid=86c6b0&_nc_eui2=AeEJntlOHIjNwx9MhwiHnDU-R1fQJgdTMN9HV9AmB1Mw3xW9WxJqEvFjvBDYnohuPLwCCUXHQbYiNOoynb9OQfG4&_nc_ohc=Cx8D0-h-HgcQ7kNvgHb12Je&_nc_zt=23&_nc_ht=scontent.fmnl30-2.fna&_nc_gid=AHVqySvdJUJh2_Waj9J6gS4&oh=00_AYDcxEfTxCEiuFDYE8F7qcxzPlYV2XQfkKOU0OU2XgOxWQ&oe=67393F98') no-repeat center center fixed;
            background-size: cover;
            z-index: -1;
            opacity: 0.7;
        }
            
    }   

        .background{
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-size: cover;
        }

        .form-container {
            background-color: rgba(255, 255, 255, 0.95);  /* Slightly more opaque */
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);    /* Enhanced shadow */
            border-radius: 15px;
            padding: 40px;                                /* Increased padding */
            width: 100%;
            max-width: 600px;
            margin: 120px auto;                          /* Increased top margin to move it down */
            position: relative;
            z-index: 1;
            border: 1px solid rgba(255, 255, 255, 0.3);  /* Subtle border */
            backdrop-filter: blur(10px);                 /* Adds blur effect behind */
        }

        .form-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            border-color: #1e90ff;
            outline: none;
        }

        .form-group input.invalid,
        .form-group textarea.invalid,
        .form-group select.invalid {
            border-color: red;
        }

        button {
            padding: 8px 15px; /* Reduced size */
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px; /* Adjusted font size */
            width: auto;
            transition: background-color 0.3s;
        }

        button.next {
            background-color: #1e90ff;
            color: #fff;
        }

        button.next:hover {
            background-color: #0056b3;
        }

        button.back {
            background-color: #f39c12;
            color: #fff;
        }

        button.back:hover {
            background-color: #e67e22;
        }

        button.create {
            background-color: #28a745;
            color: #fff;
        }

        button.create:hover {
            background-color: #218838;
        }

        .hidden {
            display: none;
        }

        .error-message {
            color: red;
            font-size: 12px;
            margin-top: 5px;
        }
    </style>
    <script>
        function validateForm() {
            let isValid = true;
            const inputs = document.querySelectorAll('input:not([type="file"]), textarea, select');
            
            inputs.forEach(input => {
                if (input.hasAttribute('required') && input.value.trim() === '') {
                    input.classList.add('invalid');
                    isValid = false;
                } else {
                    input.classList.remove('invalid');
                }
            });

            // Specific validation for student ID
            const studentId = document.getElementById('student_id');
            if (studentId.value.length !== 12) {
                studentId.classList.add('invalid');
                document.getElementById('student_id_error').textContent = 'Please enter 12 digit number';
                isValid = false;
            }

            // Password match validation
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');
            if (password.value !== confirmPassword.value) {
                password.classList.add('invalid');
                confirmPassword.classList.add('invalid');
                isValid = false;
            }

            // Contact number validation
            const contactNumber = document.getElementById('contact_number');
            if (contactNumber.value.length !== 11) {
                contactNumber.classList.add('invalid');
                document.getElementById('contact_number_error').textContent = 'Please enter 11 digit number';
                isValid = false;
            }

            // Email validation
            const email = document.getElementById('email');
            if (!email.value.includes('@')) {
                email.classList.add('invalid');
                document.getElementById('email_error').textContent = 'Please enter a valid email address';
                isValid = false;
            }

            // Date of birth validation
            const dob = new Date(document.getElementById('date_of_birth').value);
            const maxDate = new Date('2009-12-31');
            if (dob > maxDate) {
                document.getElementById('date_of_birth').classList.add('invalid');
                document.getElementById('dob_error').textContent = 'Date of birth must be before 2009';
                isValid = false;
            }

            return isValid;
        }

        function showNextStep(step) {
            const currentStep = step === 1 ? 'step1' : 'step2';
            const nextStep = step === 1 ? 'step2' : 'step1';

            const inputs = document.querySelectorAll(`#${currentStep} input:not([type="file"]), #${currentStep} textarea, #${currentStep} select`);
            let isValid = true;

            inputs.forEach(input => {
                if (input.hasAttribute('required') && input.value.trim() === '') {
                    input.classList.add('invalid');
                    isValid = false;
                } else {
                    input.classList.remove('invalid');
                }
            });

            if (isValid) {
                document.getElementById(currentStep).classList.add('hidden');
                document.getElementById(nextStep).classList.remove('hidden');
            }
        }

        document.getElementById('student_id').addEventListener('input', function() {
            const studentId = this.value;
            const errorElement = document.getElementById('student_id_error');
            
            // Remove any non-numeric characters
            this.value = this.value.replace(/[^0-9]/g, '');
            
            if (this.value.length === 0) {
                errorElement.textContent = 'Student ID is required';
                this.classList.add('invalid');
            } else if (this.value.length !== 12) {
                errorElement.textContent = 'Please enter 12 digit number';
                this.classList.add('invalid');
            } else {
                errorElement.textContent = '';
                this.classList.remove('invalid');
            }
        });

        document.getElementById('contact_number').addEventListener('input', function() {
            const errorElement = document.getElementById('contact_number_error');
            
            // Remove any non-numeric characters
            this.value = this.value.replace(/[^0-9]/g, '');
            
            if (this.value.length === 0) {
                errorElement.textContent = 'Contact number is required';
                this.classList.add('invalid');
            } else if (this.value.length !== 11) {
                errorElement.textContent = 'Please enter 11 digit number';
                this.classList.add('invalid');
            } else {
                errorElement.textContent = '';
                this.classList.remove('invalid');
            }
        });

        document.getElementById('email').addEventListener('input', function() {
            const errorElement = document.getElementById('email_error');
            
            if (!this.value.includes('@')) {
                errorElement.textContent = 'Please enter a valid email address';
                this.classList.add('invalid');
            } else {
                errorElement.textContent = '';
                this.classList.remove('invalid');
            }
        });

        document.getElementById('date_of_birth').addEventListener('change', function() {
            const errorElement = document.getElementById('dob_error');
            const selectedDate = new Date(this.value);
            const maxDate = new Date('2009-12-31');
            
            if (selectedDate > maxDate) {
                errorElement.textContent = 'Date of birth must be before 2009';
                this.classList.add('invalid');
            } else {
                errorElement.textContent = '';
                this.classList.remove('invalid');
            }
        });
    </script>
</head>
<body>
<body>
<div class="background"></div>
    <div class="form-container">
        <h2>Create Student Account</h2>
        <form action="add_role.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
            <!-- Step 1 -->
            <div id="step1">
                <div class="form-group">
                    <label for="first_name">First Name:</label>
                    <input type="text" name="first_name" id="first_name" placeholder="Enter first name" autocomplete="off" required>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name:</label>
                    <input type="text" name="last_name" id="last_name" placeholder="Enter last name" autocomplete="off" required>
                </div>
                <div class="form-group">
                    <label for="student_id">Student ID:</label>
                    <input type="text" name="student_id" id="student_id" 
                           placeholder="Enter 12-digit student ID" 
                           maxlength="12"
                           pattern="[0-9]{12}" 
                           title="Please enter 12 digit number"
                           autocomplete="off" required>
                    <span class="error-message" id="student_id_error"></span>
                </div>
                <div class="form-group">
                    <label for="email">Email Address:</label>
                    <input type="email" name="email" id="email" placeholder="Enter email address" autocomplete="off" required>
                    <span class="error-message" id="email_error"></span>
                </div>
                <div class="form-group">
                    <label for="contact_number">Contact Number:</label>
                    <input type="text" name="contact_number" id="contact_number" 
                           placeholder="Enter 11-digit contact number" 
                           maxlength="11"
                           pattern="[0-9]{11}" 
                           title="Please enter 11 digit number"
                           autocomplete="off" required>
                    <span class="error-message" id="contact_number_error"></span>
                </div>
                <div class="form-group">
                    <label for="address">Address:</label>
                    <textarea name="address" id="address" rows="3" placeholder="Enter address" autocomplete="off" required></textarea>
                </div>
                <div class="form-group" style="display: flex; gap: 10px;">
                    <button type="button" class="back" onclick="window.location.href='add_roles.php'">Back</button>
                    <button type="button" class="next" onclick="showNextStep(1)">Next</button>
                </div>
            </div>

            <!-- Step 2 -->
            <div id="step2" class="hidden">
                <div class="form-group">
                    <label for="date_of_birth">Date of Birth:</label>
                    <input type="date" name="date_of_birth" id="date_of_birth" 
                           max="2009-12-31"
                           autocomplete="off" required>
                    <span class="error-message" id="dob_error"></span>
                </div>
                <div class="form-group">
                    <label for="profile_picture">Profile Picture:</label>
                    <input type="file" name="profile_picture" id="profile_picture" accept="image/*">
                </div>
                <div class="form-group">
                    <label for="program">Section:</label>
                    <select name="program" id="program" required>
                        <option value="" disabled selected>Select program</option>
                        <option value="STEM 1"> STEM 1: Newton</option>
                        <option value="STEM 2">STEM 2: Einstein</option>
                      
                        <!-- Add more programs as needed -->
                    </select>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" placeholder="Enter password" autocomplete="off" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm password" autocomplete="off" required>
                </div>
                <div class="form-group">
                    <button type="button" class="back" onclick="showNextStep(0)">Back</button>
                    <button type="submit" name="add_user" class="create">Create Account</button>
                </div>
                <a href="ADMIN_dashboard.php">Back to Admin Dashboard</a>

            </div>
        </form>
    </div>
</body>
</html>