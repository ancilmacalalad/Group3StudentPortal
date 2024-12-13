<?php
// At the start of your file, create the uploads directory if it doesn't exist
$upload_dir = "uploads/teacher_profiles";
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

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

// Get the last ID from both tables
$query1 = "SELECT student_id FROM studentportal ORDER BY student_id DESC LIMIT 1";
$query2 = "SELECT teacher_id FROM teachers ORDER BY teacher_id DESC LIMIT 1";

$result1 = $conn->query($query1);
$result2 = $conn->query($query2);

$last_id1 = 0;
$last_id2 = 0;

if ($result1->num_rows > 0) {
    $row = $result1->fetch_assoc();
    $last_id1 = intval($row['student_id']);
}

if ($result2->num_rows > 0) {
    $row = $result2->fetch_assoc();
    $last_id2 = intval($row['teacher_id']);
}

// Get the highest ID between the two tables
$highest_id = max($last_id1, $last_id2);
$next_id = str_pad($highest_id + 1, 5, '0', STR_PAD_LEFT);

if (isset($_POST['add_user'])) {
    // Handle file upload
    $img_url = null;
    if(isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['profile_picture']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        
        if(in_array(strtolower($filetype), $allowed)) {
            $temp_name = $_FILES['profile_picture']['tmp_name'];
            $new_filename = "teacher_" . $next_id . "." . $filetype;
            $upload_path = "uploads/teacher_profiles/" . $new_filename;
            
            if(move_uploaded_file($temp_name, $upload_path)) {
                $img_url = $upload_path;
            }
        }
    }

    // Start transaction
    $conn->begin_transaction();

    try {
        // Insert into teachers table
        $sql1 = "INSERT INTO teachers (teacher_id, first_name, last_name, email, 
                contact_number, address, passcode, subject_specialization, status, usertype, img_url) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Insert into studentportal table
        $sql2 = "INSERT INTO studentportal (student_id, first_name, last_name, email, 
                contact_number, address, passcode, usertype, img_url) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Set default values
        $status = 'active';
        $usertype = 'teacher';

        // Prepare and execute first statement (teachers table)
        if ($stmt1 = $conn->prepare($sql1)) {
            $stmt1->bind_param("sssssssssss", 
                $next_id, 
                $_POST['first_name'], 
                $_POST['last_name'], 
                $_POST['email'],
                $_POST['contact_number'],
                $_POST['address'],
                $_POST['password'],
                $_POST['subject_specialization'],
                $status,
                $usertype,
                $img_url
            );
            $stmt1->execute();
            $stmt1->close();
        }

        // Prepare and execute second statement (studentportal table)
        if ($stmt2 = $conn->prepare($sql2)) {
            $stmt2->bind_param("sssssssss",
                $next_id,
                $_POST['first_name'],
                $_POST['last_name'],
                $_POST['email'],
                $_POST['contact_number'],
                $_POST['address'],
                $_POST['password'],
                $usertype,
                $img_url
            );
            $stmt2->execute();
            $stmt2->close();
        }

        // If we got here, both inserts were successful
        $conn->commit();

        // Show success message
        echo '
        <div style="
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
            text-align: center;
            z-index: 1000;
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            width: 100%;
            max-width: 600px;">
            <h2 style="color: #28a745; margin-bottom: 20px;">✓ Account Successfully Created!</h2>
            <h3 style="color: #007bff; margin-bottom: 20px;">Back to Dashboard</h3>
            <div style="margin-top: 20px;">
                <a href="ADMIN_dashboard.php" style="
                    background-color: #007bff;
                    color: white;
                    padding: 10px 20px;
                    text-decoration: none;
                    border-radius: 5px;
                    margin-right: 10px;
                    transition: background-color 0.3s;">
                    Back to Dashboard
                </a>
                <a href="add_teacher.php" style="
                    background-color: #28a745;
                    color: white;
                    padding: 10px 20px;
                    text-decoration: none;
                    border-radius: 5px;
                    transition: background-color 0.3s;">
                    Add Another Teacher
                </a>
            </div>
        </div>';
        exit;

    } catch (Exception $e) {
        // If there's an error, roll back the transaction
        $conn->rollback();
        echo '<div>Error: ' . $e->getMessage() . '</div>';
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
    <title>Create Teacher Account</title>
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

     
    </style>
    <script>
        function validateForm() {
            let isValid = true;
            const inputs = document.querySelectorAll('input:not([type="file"]):not([readonly]), textarea, select');
            
            inputs.forEach(input => {
                // Skip validation for student_id/teacher_id fields
                if (input.id === 'student_id' || input.id === 'student_id') {
                    return;
                }
                
                if (input.hasAttribute('required') && input.value.trim() === '') {
                    input.classList.add('invalid');
                    isValid = false;
                } else {
                    input.classList.remove('invalid');
                }
            });

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

            // Password match validation
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');
            if (password.value !== confirmPassword.value) {
                password.classList.add('invalid');
                confirmPassword.classList.add('invalid');
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

        // Function to fetch and display the next ID
        function fetchNextID() {
            fetch('get_next_id.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('student_id').value = data;
                })
                .catch(error => console.error('Error:', error));
        }

        // Call the function when page loads
        window.onload = fetchNextID;
    </script>
</head>
<body>
<body>
<div class="background"></div>
    <div class="form-container">
        <h2>Create Teacher Account</h2>
        <form action="add_teacher.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
            <!-- Step 1 -->
            <div id="step1">
                <div class="form-group">
                    <label for="teacher_id">Teacher ID:</label>
                    <input type="text" name="teacher_id" id="teacher_id" value="<?php echo $next_id; ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="profile_picture">Profile Picture:</label>
                    <input type="file" name="profile_picture" id="profile_picture" accept="image/*">
                    <div id="image_preview" style="margin-top: 10px; max-width: 200px;">
                        <img id="preview" style="display: none; max-width: 100%; height: auto;">
                    </div>
                </div>

                <div class="form-group">
                    <label for="first_name">First Name:</label>
                    <input type="text" name="first_name" id="first_name" required>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name:</label>
                    <input type="text" name="last_name" id="last_name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email Address:</label>
                    <input type="email" name="email" id="email" required>
                    <span class="error-message" id="email_error"></span>
                </div>
                <div class="form-group">
                    <label for="contact_number">Contact Number:</label>
                    <input type="text" name="contact_number" id="contact_number" maxlength="11">
                    <span class="error-message" id="contact_number_error"></span>
                </div>
                <div class="form-group">
                    <label for="address">Address:</label>
                    <textarea name="address" id="address" rows="3"></textarea>
                </div>
                <div class="form-group" style="display: flex; gap: 10px;">
                    <a href="add_roles.php" class="back" style="
                        background-color: #f39c12;
                        color: #fff;
                        padding: 8px 15px;
                        border-radius: 5px;
                        text-decoration: none;
                        font-size: 14px;
                        transition: background-color 0.3s;">Back</a>
                    <button type="button" class="next" onclick="showNextStep(1)">Next</button>
                </div>
            </div>

            <!-- Step 2 -->
            <div id="step2" class="hidden">
                <div class="form-group">
                    <label for="subject_specialization">Subject Specialization:</label>
                    <select name="subject_specialization" id="subject_specialization" required>
                        <option value="" disabled selected>Select subject</option>
                        <option value="Oral Communication">Oral Communication</option>
                        <option value="General Math">General Math</option>
                        <option value="21st Century">21st Century</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" name="confirm_password" id="confirm_password" required>
                </div>
                <div class="form-group">
                    <button type="button" class="back" onclick="showNextStep(0)">Back</button>
                    <button type="submit" name="add_user" class="create">Create Account</button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>