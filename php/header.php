<!DOCTYPE html>
<html>
<head>
    <title>Covoiturage</title>
    <!-- Include Font Awesome stylesheet -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* General Body Styling */
        body {
            font-family: 'DyeLine', sans-serif; /* Fancy font family */
            margin: 0;
            padding: 0;
        }

        /* Header and Navigation Styling */
        header {
            position: relative;
            top: 0;
            left: 0;
            right: 0;
            z-index: 10;
            background-color: transparent;
            padding: 20px 0;
            overflow: hidden;
            border-bottom: 1.7px solid #b5c2b7; /* Border style */
        }

        .main-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 0 auto;
            width: 80%;
            position: relative;
        }

        .logo {
            font-size: 28px; /* Larger font size */
            font-weight: bold;
            text-transform: uppercase;
            font-family: 'Arial', sans-serif;
            letter-spacing: 3px; /* Increased letter spacing */
            text-shadow: 2px 2px 2px rgba(0,0,0,0.2); /* Shadow effect */
            display: flex;
            align-items: center;
            white-space: nowrap;
        }

        .logo a {
            text-decoration: none;
            color: #001233; /* Override default link color */
        }

        .logo a:hover,
        .logo a:active,
        .logo a:focus {
            color: #001233; /* Maintain consistent color */
        }

        .menu {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center; /* Center horizontally */
            justify-content: center; /* Center vertically */
        }

        .menu li {
            margin-left: 50px;
        }

        .menu li a {
            text-decoration: none;
            color: black;
        }

        /* Responsive Design */
        @media screen and (max-width: 768px) {
            .main-nav {
                flex-direction: column;
                align-items: flex-start;
            }

            .menu {
                flex-direction: column;
                align-items: flex-start;
            }

            .menu li {
                margin-bottom: 10px;
            }

            .logo {
                font-size: 24px; /* Adjust font size for smaller screens */
            }
        }
    </style>
</head>
<body>
    <header>
        <nav class="main-nav">
            <div class="logo">
                <a href="http://localhost/free-sgbd203/index.php">COVOITURAGE CAMPUS</a>
            </div>
            <ul class="menu">
                <li><a href="http://localhost/free-sgbd203/php/login.php"><i class="fas fa-user"></i> Sign up </a></li>
                <li><a href="http://localhost/free-sgbd203/php/consultation.php">Consultation</a></li>
                <li><a href="http://localhost/free-sgbd203/php/AboutUs.php"> AboutUs</a></li>
                <li><a href="http://localhost/free-sgbd203/php/contact.php">Contact</a></li>
            </ul>
        </nav>
    </header>
</body>
</html>