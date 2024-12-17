<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Covoiturage</title>
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        padding-top: 60px;
        background-color: #ffffff;
        color: #000000;
        margin: 0;
        transition: background-color 0.5s ease;
    }

    header {
        background-color: #333;
        color: white;
        padding: 20px 0;
        text-align: center;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 1000;
        transition: background-color 0.5s ease;
    }

    .content-container {
        width: 80%;
        margin: 80px auto;
        padding: 40px;
        background-color: #fff;
        border-radius: 15px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        transition: transform 0.5s ease, box-shadow 0.5s ease;
    }

    .content-container:hover {
        transform: scale(1.02);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
    }

    h2, h3 {
        text-align: center;
        color: #333;
        margin-bottom: 30px;
        transition: color 0.5s ease;
    }

    .team-member {
        display: flex;
        align-items: center;
        margin-bottom: 30px;
        background-color: #f2f2f2;
        border-radius: 10px;
        transition: all 0.5s ease-in-out;
    }

    .team-member:hover {
        background-color: #e2e2e2;
        transform: translateY(-10px) scale(1.05);
    }

    .team-member img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        margin-right: 20px;
        border: 3px solid #333;
        transition: all 0.5s ease;
    }

    .team-member img:hover {
        border-color: #555;
        transform: rotate(360deg);
    }

    .team-member h4, .team-member p {
        margin: 0;
        transition: color 0.5s ease;
    }

    .team-member:hover h4, .team-member:hover p {
        color: #555;
    }

    @media screen and (max-width: 600px) {
        .content-container, .team-member {
            width: 95%;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .team-member img {
            margin-bottom: 10px;
        }
    }
</style>

</head>
<body>
    <?php include('header.php'); ?>

    <div class="content-container">
    <h2>About Us</h2>
    <p>Welcome to our campus covoiturage website! We are committed to making campus travel easier, more affordable, and environmentally friendly. Our platform connects students and staff for convenient carpooling experiences within the campus community.</p>
    
    <h3>Our Team</h3>
    <div class="team-member">
        <img src="../img/mb.jpeg" alt="Mohammed Bouhaja">
        <div>
            <h4>Mohammed Bouhaja - Founder</h4>
            <p>As a tech enthusiast and environmental advocate, Mohammed envisioned a platform that not only eases daily commutes but also contributes to reducing carbon emissions. He is dedicated to leveraging technology for sustainable solutions.</p>
        </div>
    </div>
    <div class="team-member">
        <img src="../img/mh.jpeg" alt="Marwa Hajji Laamouri">
        <div>
            <h4>Marwa Hajji Laamouri - Co-Founder</h4>
            <p>Marwa, a problem solver at heart and a tech enthusiast, combines her technical acumen with innovative thinking to tackle challenges in sustainable transportation. Her keen insights and passion for technology play a crucial role in developing practical, user-friendly solutions for our covoiturage platform.</p>
        </div>
    </div>
    <div class="team-member">
        <img src="../img/md.jpeg" alt="Mohamed Dyae Chellaf">
        <div>
            <h4>Mohamed Dyae Chellaf - Co-Founder</h4>
            <p>Dyae's expertise in sustainable urban planning and his commitment to reducing urban traffic congestion have been instrumental in shaping the strategic direction of our service.</p>
        </div>
    </div>
    <div class="team-member">
        <img src="../img/rk.jpeg" alt="Mohamed Reda Elkhader">
        <div>
            <h4>Mohamed Reda Elkhader - Co-Founder</h4>
            <p>Reda, an avid technology and green living advocate, focuses on integrating advanced, user-friendly features into our platform, making covoiturage an easy choice for campus commuters.</p>
        </div>
    </div>

    <h3>Our Mission</h3>
    <p>Our mission is to foster a culture of sharing and sustainability on campus. By connecting drivers with passengers, we aim to create a more connected, environmentally responsible community. Join us in transforming campus travel into a more social, cost-effective, and eco-friendly experience.</p>
</div>


</body>
</html>
