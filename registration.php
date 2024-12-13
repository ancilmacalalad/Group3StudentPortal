<?php
// Add this near the top of the file, after <?php
session_start();

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$dbname = "studentportal";

try {
    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    die("Database connection error: " . $e->getMessage());
}

// Function to sanitize input data
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Insert data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Student Information
    $student_id = sanitize_input($_POST['lrn']);
    $first_name = sanitize_input($_POST['firstName']);
    $last_name = sanitize_input($_POST['lastName']);
    $email = sanitize_input($_POST['email']);
    $dob = sanitize_input($_POST['dob']);
    $course = sanitize_input($_POST['course']);
    $gender = sanitize_input($_POST['gender']);
    $contact_number = sanitize_input($_POST['contact']);
    
    // New fields
    $emergency_name = sanitize_input($_POST['emergency_name']);
    $emergency_relation = sanitize_input($_POST['emergency_relation']);
    $emergency_contact = sanitize_input($_POST['emergency_contact']);
    $father_name = sanitize_input($_POST['father_name']);
    $father_occupation = sanitize_input($_POST['father_occupation']);
    $mother_name = sanitize_input($_POST['mother_name']);
    $mother_occupation = sanitize_input($_POST['mother_occupation']);

    // Profile Picture Upload
    $profile_picture = "";
    if(isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] == 0) {
        $profile_picture = $_FILES['profilePicture']['name'];
        $profile_picture_tmp = $_FILES['profilePicture']['tmp_name'];
        move_uploaded_file($profile_picture_tmp, "uploads/" . $profile_picture);
    }

    // Updated SQL Query to Insert Data
    $sql = "INSERT INTO student (
        student_id, first_name, last_name, email, dob, course, gender, 
        contact_number, profile_picture, emergency_name, emergency_relation, 
        emergency_contact, father_name, father_occupation, mother_name, mother_occupation
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssssssss", 
        $student_id, $first_name, $last_name, $email, $dob, $course, $gender, 
        $contact_number, $profile_picture, $emergency_name, $emergency_relation, 
        $emergency_contact, $father_name, $father_occupation, $mother_name, $mother_occupation
    );

    if ($stmt->execute()) {
        $_SESSION['registration_success'] = true;
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "<div class='error'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration Form</title>
  <style>
    body {
       background: url('https://scontent.fmnl30-2.fna.fbcdn.net/v/t1.6435-9/144479337_100276648761424_1300734801011337603_n.jpg?_nc_cat=110&ccb=1-7&_nc_sid=86c6b0&_nc_eui2=AeEJntlOHIjNwx9MhwiHnDU-R1fQJgdTMN9HV9AmB1Mw3xW9WxJqEvFjvBDYnohuPLwCCUXHQbYiNOoynb9OQfG4&_nc_ohc=Cx8D0-h-HgcQ7kNvgHb12Je&_nc_zt=23&_nc_ht=scontent.fmnl30-2.fna&_nc_gid=AHVqySvdJUJh2_Waj9J6gS4&oh=00_AYDcxEfTxCEiuFDYE8F7qcxzPlYV2XQfkKOU0OU2XgOxWQ&oe=67393F98') no-repeat center center fixed;
      background-size: cover;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 0;
      padding: 20px;
    }

    .form-container {
      background: rgba(255, 255, 255, 0.95); /* Made slightly transparent */
      width: 400px;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    .form-container h2 {
      margin-bottom: 20px;
      font-size: 22px;
      text-align: center;
    }
    .form-group {
      margin-bottom: 15px;
    }
    label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }
    input, select, button {
      width: 100%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    button {
      background-color: #007bff;
      color: white;
      border: none;
      cursor: pointer;
    }
    button:hover {
      background-color: #0056b3;
    }
    .error {
      color: red;
      font-size: 14px;
    }
    .profile-preview {
      margin-top: 10px;
      display: block;
      max-width: 100px;
      max-height: 100px;
      border: 1px solid #ccc;
    }
    .success-container {
      text-align: center;
      padding: 20px;
    }

    .back-button {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 20px;
      background-color: #28a745;
      color: white;
      text-decoration: none;
      border-radius: 4px;
      width: 150px !important;
      margin: 10px auto;
    }

    .back-button:hover {
      background-color: #218838;
    }
  </style>
</head>
<body>
  <div class="form-container">
    <?php if (isset($_SESSION['registration_success'])): ?>
      <div class="success-container">
        <h2>ACCOUNT IS REGISTERED SUCCESSFULLY</h2>
        <a href="index.php" class="back-button">Back</a>
      </div>
      <?php 
      // Clear the session variable
      unset($_SESSION['registration_success']);
      ?>
    <?php else: ?>
      <h2>Registration Form</h2>
      <form action="registration.php" method="POST" enctype="multipart/form-data">
      <!-- First Name -->
      <div class="form-group">
        <label for="firstName">First Name:</label>
        <input type="text" id="firstName" name="firstName" required>
      </div>
      <!-- Last Name -->
      <div class="form-group">
        <label for="lastName">Last Name:</label>
        <input type="text" id="lastName" name="lastName" required>
      </div>
      <!-- LRN -->
      <div class="form-group">
        <label for="lrn">LRN:</label>
        <input type="text" id="lrn" name="lrn" maxlength="12" required>
      </div>
      <!-- Email -->
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
      </div>
      <!-- Date of Birth -->
      <div class="form-group">
        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob" required>
      </div>
      <!-- Course -->
      <div class="form-group">
        <label for="course">Course:</label>
        <select id="course" name="course" required>
          <option value="" disabled selected>Select Course</option>
          <option value="STEM">STEM</option>
        </select>
      </div>
      <!-- Contact Number -->
      <div class="form-group">
        <label for="contact">Contact Number:</label>
        <input type="tel" id="contact" name="contact" pattern="[0-9]{10,11}" required>
      </div>
      <!-- Gender -->
      <div class="form-group">
        <label>Gender:</label>
        <input type="radio" id="male" name="gender" value="Male" required>
        <label for="male">Male</label>
        <input type="radio" id="female" name="gender" value="Female" required>
        <label for="female">Female</label>
      </div>
      <!-- Profile Picture -->
      <div class="form-group">
        <label for="profilePicture">Profile Picture (2x2):</label>
        <input type="file" id="profilePicture" name="profilePicture" accept="image/*" required>
      </div>
      <!-- Emergency Contact Information -->
      <div class="form-group">
        <label for="emergency_name">Emergency Contact Name:</label>
        <input type="text" id="emergency_name" name="emergency_name" required>
      </div>

      <div class="form-group">
        <label for="emergency_relation">Relationship:</label>
        <input type="text" id="emergency_relation" name="emergency_relation" required>
      </div>

      <div class="form-group">
        <label for="emergency_contact">Emergency Contact Number:</label>
        <input type="tel" id="emergency_contact" name="emergency_contact" pattern="[0-9]{10,11}" required>
      </div>

      <!-- Parent Information -->
      <div class="form-group">
        <label for="father_name">Father's Name:</label>
        <input type="text" id="father_name" name="father_name" required>
      </div>

      <div class="form-group">
        <label for="father_occupation">Father's Occupation:</label>
        <input type="text" id="father_occupation" name="father_occupation" required>
      </div>

      <div class="form-group">
        <label for="mother_name">Mother's Name:</label>
        <input type="text" id="mother_name" name="mother_name" required>
      </div>

      <div class="form-group">
        <label for="mother_occupation">Mother's Occupation:</label>
        <input type="text" id="mother_occupation" name="mother_occupation" required>
      </div>
      <!-- Submit Button -->
      <button type="submit">Submit</button>
      <div class="form-group" style="margin-top: 10px; text-align: center;">
        <a href="index.php" class="back-button">Back</a>
      </div>
    </form>
    <?php endif; ?>
  </div>
</body>
</html>