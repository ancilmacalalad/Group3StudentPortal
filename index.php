<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SJSHS STEM Student Portal</title>
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
        <p>Welcome! Good to see us all here! To give you a better experience of academics, this portal has been created. You will be able to log in and manage your record or profile, access critical resources, update your information with ease, and stay informed of your academic progress. Let's do this!</p>
        <button class="cta-button" onclick="location.href='user_login.php'">Login to Dashboard</button>
    </main>

    <footer>
        <p>&copy; 2024 STEM Student Portal. All rights reserved.</p>
    </footer>
</body>

</html>
