<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - STEM Student Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
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
          flex-direction: column;
          min-height: 100vh;
          width: 100%; /* Make body full width */
      }

      header {
          position: relative;
          text-align: center;
          padding: 60px 20px;
          overflow: hidden;
      }

      .background {
          position: absolute;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background-image: url('https://scontent.fmnl30-2.fna.fbcdn.net/v/t1.6435-9/144479337_100276648761424_1300734801011337603_n.jpg?_nc_cat=110&ccb=1-7&_nc_sid=86c6b0&_nc_eui2=AeEJntlOHIjNwx9MhwiHnDU-R1fQJgdTMN9HV9AmB1Mw3xW9WxJqEvFjvBDYnohuPLwCCUXHQbYiNOoynb9OQfG4&_nc_ohc=Cx8D0-h-HgcQ7kNvgHb12Je&_nc_zt=23&_nc_ht=scontent.fmnl30-2.fna&_nc_gid=AHVqySvdJUJh2_Waj9J6gS4&oh=00_AYDcxEfTxCEiuFDYE8F7qcxzPlYV2XQfkKOU0OU2XgOxWQ&oe=67393F98');
          background-size: cover;
          background-position: 50% 13%;
          opacity: 0.5;
          z-index: -1;
      }

      header h1 {
          font-weight: 600;
          font-size: 3rem;
          letter-spacing: 1px;
          color: black;
          position: relative;
          z-index: 1;
          text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);
      }

      nav {
          background-color: #333;
          padding: 5px 0;
          text-align: center;
      }

      nav a {
          color: #fff;
          text-decoration: none;
          margin: 0 20px;
          padding: 10px 20px;
          display: inline-block;
          font-weight: 600;
          transition: background-color 0.3s ease, color 0.3s ease;
          border-radius: 5px;
      }

      nav a:hover {
          background-color: #4CAF50;
          color: #fff;
      }

      main {
          max-width: 900px;
          margin: 20px auto;
          padding: 40px;
          text-align: center;
          background-color: white;
          border-radius: 10px;
          box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
          flex-grow: 1;
      }

      main p {
          font-size: 1.3rem;
          margin-bottom: 20px;
          color: #555;
      }

      .cta-button {
          padding: 15px 30px;
          background-color: blue;
          color: white;
          border: none;
          border-radius: 5px;
          font-size: 1.1rem;
          font-weight: 600;
          cursor: pointer;
          transition: background-color 0.3s ease, transform 0.3s ease;
          box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
      }

      .cta-button:hover {
          background-color: yellowgreen;
          transform: translateY(-3px);
      }

      .cta-button:active {
          background-color: blue;
      }

      footer {
          background-color: #333;
          color: white;
          text-align: center;
          padding: 10px 0;
          box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
      }

      footer p {
          margin: 0;
          font-size: 0.9rem;
      }

      @media (max-width: 768px) {
          header h1 {
              font-size: 2rem;
          }

          nav a {
              padding: 5px 5px;
          }

          main p {
              font-size: 1.1rem;
          }

          .cta-button {
              font-size: 1rem;
          }
      }

      @media (max-width: 480px) {
          nav a {
              display: block;
              margin: 5px 0;
          }

          main {
              padding: 20px;
          }
      }
    </style>
</head>
<body>
    <header>
    <div class="background"></div>
        <h1>SJSHS Student Portal</h1>
    </header>
    <nav>
        <a href="index.php">Home</a>
        <a href="about.php">About</a>
        <a href="registration.php">Register</a>
        <a href="contactUS.php">Contact Us</a>
        <a href="STEMResources.php">STEM Resources</a>
    </nav>
    <main>
        <h2>Mission</h2>
        <p>To protect and promote the right of every Filipino to quality, equitable, culture-based, and complete basic education where:

Students learn in a child-friendly, gender-sensitive, safe, and motivating environment.

Teachers facilitate learning and constantly nurture every learner.

Administrators and staff, as stewards of the institution, ensure an enabling and supportive environment for effective learning to happen.

Family, community, and other stakeholders are actively engaged and share responsibility for developing life-long learners.</p>
        
        <h2>Vision</h2>
        <p>We dream of Filipinos who passionately love their country and whose values and competencies enable them to realize their full potential and contribute meaningfully to building the nation.

As a learner-centered public institution, the Department of Education continuously improves itself to better serve its stakeholders.</p>

        <div class="e-counseling">
            <h2>E-Counseling Services</h2>
            <p>Our e-counseling services offer students the opportunity to connect with academic advisors and counselors online. We provide guidance on:</p>
            <ul>
                <li>Course selection and academic planning</li>
                <li>Study techniques and time management</li>
                <li>Emotional support and mental health resources</li>
            </ul>
            <p><a href="registration.php">Enroll Now:  </a> Click the link to access the enrollment form.</p>
            <p>For more information, please contact our counseling team via the <a href="contactUs.php">Contact Us</a> page.</p>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 STEM Student Portal. All rights reserved.</p>
    </footer>
</body>
</html>
