<!DOCTYPE html>
<html>
<head>
    <title>Covoiturage</title>
    <link rel="icon" type="image/x-icon" href="LOGOV1.0.png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet"> <!-- For FontAwesome icons -->
    <style>
    body {
        font-family: 'Open Sans', sans-serif;
        background-color: #ffffff;
        color: #000000;
        margin: 0;
        padding-top: 80px;
    }

    .row, .row1 {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        text-align: center;
    }

    .column, .column1 {
        flex: 1;
        min-width: 250px; /* Assure une largeur minimale pour la responsivité */
        box-sizing: border-box;
        padding: 20px;
    }

    .column1 {
        background-color: #f0f0f0;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin: 10px;
        transition: transform 0.3s ease; /* Animation de transformation */
    }

    .column1:hover {
        transform: scale(1.05); /* Agrandissement au survol */
    }

    h2 {
        color: #000000;
        margin-bottom: 15px;
    }

    p {
        color: #333333;
        line-height: 1.6;
        font-size: 14px;
    }

    .button-container {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
    }

    .button {
        background-color: #000000;
        color: #ffffff;
        border: none;
        padding: 15px 32px;
        text-align: center;
        display: inline-block;
        font-size: 16px;
        border-radius: 8px;
        margin: 10px;
        transition: background-color 0.3s ease, transform 0.3s ease;
        width: auto;
    }

    .button:hover {
        background-color: #555555;
        transform: translateY(-5px);
    }

    img {
        max-width: 150%; /* Assure que l'image est responsive */
        height: auto;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-top: 50px;
    }

    h1 {
        text-align: center;
        margin-bottom: 20px;
    }

    #statsContainer {
        display: flex;
        justify-content: center; /* Center blocks horizontally */
        flex-wrap: wrap; /* Allow the blocks to wrap if necessary */
        gap: 20px; /* Space between blocks */
    }

    .statBlock {
        background-color: #333; /* Dark background */
        color: white; /* White text */
        padding: 20px;
        border-radius: 5px; /* Rounded corners */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Box shadow for depth */
        flex-basis: calc(33.333% - 40px); /* Take up one-third of the container width minus the gap */
        display: flex; /* Use flex layout within each block */
        flex-direction: column; /* Stack children vertically */
        justify-content: center; /* Center content vertically */
        align-items: center; /* Center content horizontally */
        min-width: 250px; /* Minimum width of each stat block */
        max-width: 300px; /* Maximum width of each stat block */
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 10px;
        text-align: center;
    }

    th {
        font-size: 18px;
    }

    td {
        font-size: 22px;
        font-weight: bold;
    }

    /* Optional: Animations for the stat blocks */
    .statBlock {
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 0.5s ease forwards;
        animation-delay: 0.2s;
    }

    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Staggered animation delays */
    .statBlock:nth-child(1) { animation-delay: 0.2s; }
    .statBlock:nth-child(2) { animation-delay: 0.4s; }
    .statBlock:nth-child(3) { animation-delay: 0.6s; }
</style>


</head>
<body>
    <?php include('php/header.php'); ?>

    <div class="row">
        <div class="column">
            <img src="./img/voiture-1.jpg" alt="car">    
        </div>
        <div class="column">
            
            <div class="button-container">
                <button class="button" id="searchButton"><i class="fas fa-search"></i> Search for a ride</button>
                <button class="button" id="publishButton"><i class="fas fa-plus"></i> Post a trip</button>
                <button class="button" id="driverSpace"><i class="fas fa-circle"></i>Driver space</button>
                <button class="button" id="reservation"><i class="fas fa-car"></i>passenger space</button>
            </div>

            <div class="row" id="row_ad">
                <div class="card">
                    <h2>Pick your next adventure ...</h2>
                    <h2>... Paris ?</h2>
                    <h2>... Toulouse ?</h2>
                    <h2>... Mulhouse ?</h2>
                    <h2>... Lyon ?</h2>
                </div>
            </div>
        </div>
    </div>

    <h1>  Main statistics</h1>

<div id="statsContainer">
    <!-- Moyenne des passagers -->
    <div class="statBlock">
    <?php
        include('connect.php');

        $query = "SELECT  AVG(subquery.Distances_Jour) AS Moyenne_Distance_Jour
        FROM (
            SELECT t.DATE_DEPART, SUM(t.DISTANCE_TOTAL) AS Distances_Jour
            FROM TRAJET t
            GROUP BY t.DATE_DEPART
        ) AS subquery;";
        if ($result = $conn->query($query)) {
            $row = $result->fetch_assoc();
            echo '<table>';
            echo '<tr><th>Average distance per day</th></tr>';
            echo '<tr>';
            echo '<td>' . $row['Moyenne_Distance_Jour'].' KM' . '</td>';
            echo '</tr>';
            echo '</table>';
        } else {
            echo '<div id="result"><h3>Erreur :</h3>';
            echo '<p>' . $conn->error . '</p>';
            echo '</div>';
        }
        $conn->close();
    ?>
    </div>
    
    <!-- Moyenne des distances parcourues -->
    <div class="statBlock">
    <?php
        include('connect.php');

        $query = "SELECT AVG(nombre_passagers) AS moyenne_passagers_par_trajet
        FROM (
            SELECT r.NUM_TRAJET, COUNT(r.NUM_PASSAGER) AS nombre_passagers
            FROM RESERVATION r
            WHERE r.VALIDATION_RESERVATION = 'TRUE'
            GROUP BY r.NUM_TRAJET
        ) AS passagers_par_trajet;
        ";
        if ($result = $conn->query($query)) {
            $row = $result->fetch_assoc();
            echo '<table>';
            echo '<tr><th>Average Passengers per trip</th></tr>';
            echo '<tr>';
            echo '<td>' . $row['moyenne_passagers_par_trajet'] . '</td>';
            echo '</tr>';
            echo '</table>';
        } else {
            echo '<div id="result"><h3>Erreur :</h3>';
            echo '<p>' . $conn->error . '</p>';
            echo '</div>';
        }
        $conn->close();
    ?>
    </div>
    
    <!-- Classement des meilleurs conducteurs -->
    <div class="statBlock">
    <?php
    include('connect.php');

    // Requête pour obtenir le conducteur le mieux classé
    $query = "SELECT e.NOM, e.PRENOM, eval.NOTE AS avis
              FROM CONDUCTEUR c
              JOIN ETUDIANT e ON c.NUM_CONDUCTEUR = e.NUM_ETUDIANT
              JOIN EVALUATION eval ON eval.NUM_ETUDIANT_EVALUE = c.NUM_CONDUCTEUR
              ORDER BY eval.NOTE DESC
              LIMIT 1;"; // Limite à un seul résultat

    if ($result = $conn->query($query)) {
        echo '<table>';
        echo '<tr><th>Our best driver</th></tr>';
        if($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row['NOM'] .' '. $row['PRENOM'] . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo '<div id="result"><h3>Erreur :</h3>';
        echo '<p>' . $conn->error . '</p>';
        echo '</div>';
    }
    $conn->close();
?>

    </div>

    <!-- Average Rating Given by Passagers -->
<div class="statBlock">
<?php
    include('connect.php'); // Make sure your connection details are correct

    $query = "SELECT AVG(NOTE) AS Average_Rating FROM EVALUATION;";
    if ($result = $conn->query($query)) {
        $row = $result->fetch_assoc();
        echo '<table>';
        echo '<tr><th>Average Passenger Reviews</th></tr>';
        echo '<tr>';
        echo '<td>' . number_format($row['Average_Rating'], 2).'/5.00' . '</td>'; // Format to 2 decimal places
        echo '</tr>';
        echo '</table>';
    } else {
        echo '<div id="result"><h3>Erreur :</h3>';
        echo '<p>' . $conn->error . '</p>';
        echo '</div>';
    }
    $conn->close();
?>
</div>

</div>


    <div class="row1">
        <div class="column1">
            <h2>Share meaningful moments...</h2>
            <p>It's about sharing meaningful moments. Each journey offers a chance to connect, share stories, and create memories with fellow travelers. Join us and turn every ride into an enriching experience.</p>
        </div>
        <div class="column1">
            <h2>At a low price...</h2>
            <p>Wherever you're going with carpooling, find the perfect ride among our wide selection of destinations at low prices.</p>
        </div>
        <div class="column1">
            <h2>Within a protected environment...</h2>
            <p>In our carpooling community, safety and trust are paramount. We are committed to providing a protected environment where every member feels secure and respected.</p>
        </div>
    </div>

    <script>
        document.getElementById('searchButton').addEventListener('click', function() {
            window.location.href = 'http://localhost/free-sgbd203/php/trajet.php';
        });

        document.getElementById('publishButton').addEventListener('click', function() {
            window.location.href = 'http://localhost/free-sgbd203/php/ajout_trajet.php';
        });

        document.getElementById('driverSpace').addEventListener('click', function() {
            window.location.href = 'http://localhost/free-sgbd203/php/espace_conducteur/index_conducteur.php';
        });
        document.getElementById('reservation').addEventListener('click', function() {
            window.location.href = 'http://localhost/free-sgbd203/php/espace_passager/index_passager.php';
        });
    </script>




<?php include('php/footer.php'); ?>
</body>
</html>
