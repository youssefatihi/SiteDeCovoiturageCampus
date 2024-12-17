<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trip information</title>
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        padding-top: 60px;
        background-color: #f2f2f2; /* Fond légèrement gris */
        margin: 0;
        color: #000000; /* Texte noir pour le contraste */
    }

    header {
        width: 100%;
        position: fixed;
        z-index: 1000;
        background: #333;
        color: white;
        padding: 20px 0;
        text-align: center;
    }

    .row {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }

    .column {
        flex: 1;
        padding: 10px;
        text-align: center;
    }

    .button {
        background-color: #333; /* Boutons en gris foncé */
        color: white;
        padding: 15px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s, transform 0.3s;
    }

    .button:hover {
        background-color: #555; /* Gris plus clair au survol */
        transform: scale(1.05);
    }

    #container {
        width: 80%;
        margin: 100px auto;
        background: white;
        padding: 100px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        text-align: center;
        font-size: 0.8rem;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        box-shadow: 0 2px 3px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    th, td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #333;
        color: white;
    }

    tr:hover {
        background-color: #ddd; /* Fond gris clair au survol */
    }

    button, input[type=submit] {
        background-color: #333;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        margin: 10px 0;
    }

    .form-section input {
        width: calc(50% - 20px);
        padding: 10px;
        margin: 5px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    @media screen and (max-width: 600px) {
        .form-section input {
            width: calc(100% - 20px);
        }
    }
</style>
</head>
<body>
<?php 
include('header.php'); 
include('../connect.php');
?>

<div id="container">
    <?php
    if (isset($_GET['num_trajet'])) {
        $num_trajet = $_GET['num_trajet'];

        // Info Trajet
        $query_trajet = "SELECT * FROM TRAJET WHERE NUM_TRAJET=?";
        $stmt_trajet = $conn->prepare($query_trajet);
        $stmt_trajet->bind_param("i", $num_trajet);
        executeAndDisplay($stmt_trajet, "Info Trajet", 
            ['NUM_IMMATRICULE', 'DATE_DEPART', 'DATE_ARRIVEE', 'ADRESSE_ARRIVEE', 'CODE_POSTAL', 'NBR_ESCALES', 'PRIX_KILOMETRAGE', 'DISTANCE_TOTAL', 'DUREE_ESTIME']);

        // Info conducteur
        $query_conducteur = "SELECT E.NOM, E.PRENOM
                            FROM ETUDIANT E
                            JOIN CONDUCTEUR C ON C.NUM_CONDUCTEUR = E.NUM_ETUDIANT
                            JOIN VOITURE V ON C.NUM_CONDUCTEUR = V.NUM_CONDUCTEUR
                            JOIN TRAJET J ON J.NUM_IMMATRICULE = V.NUM_IMMATRICULE
                            WHERE J.NUM_TRAJET=?";
        $stmt_conducteur = $conn->prepare($query_conducteur);
        $stmt_conducteur->bind_param("i", $num_trajet);
        executeAndDisplay($stmt_conducteur, "Info conducteur", ['NOM', 'PRENOM']);
    }

    function executeAndDisplay($stmt, $heading, $columns) {
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            echo "<h2>$heading</h2>";
            if ($result->num_rows > 0) {
                echo '<table>';
                echo '<tr>';
                foreach ($columns as $column) {
                    echo "<th>$column</th>";
                }
                echo '</tr>';
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>'; // Start new table row
                    foreach ($columns as $column) {
                        echo '<td>' . $row[$column] . '</td>';
                    }
                    echo '</tr>';
                }
                echo '</table>';
            } else {
                echo "No result found.";
            }
            $stmt->close();
        } else {
            echo "Erreur: " . $stmt->error;
        }
    }

    $conn->close();
    ?>
</div>
<div class="row">
    <div class="column">
        <button class="button" id="ProposerEscale">Suggest stopover</button>
    </div>
    <div class="column">
        <button class="button" id="ReserverTrajet">Book trip</button>
        <div id="reservationForm" style="display: none;">
            <!-- Form for reservation -->
            <form action="book_trajet.php<?php if(isset($_GET['num_trajet'])) { echo '?num_trajet=' . $_GET['num_trajet']; } ?>" method="post" id="reservationData">
                <input type="text" name="num_etudiant" placeholder="Student number" required><br>
                <input type="submit" value="Submit">
            </form>
        </div>
    </div>
</div>

<?php
if (isset($_GET['num_trajet'])) {
    $num_trajet = $_GET['num_trajet'];
    echo "
    <script>
        document.getElementById('ProposerEscale').addEventListener('click', function() {
            window.location.href = 'http://localhost/free-sgbd203/php/ajout_escale.php?num_trajet=$num_trajet';
        });

        document.getElementById('ReserverTrajet').addEventListener('click', function() {
            // Display the reservation form
            document.getElementById('reservationForm').style.display = 'block';
        });
    </script>";
} else {
    echo "ERROR";
}
?>


</body>
</html>
