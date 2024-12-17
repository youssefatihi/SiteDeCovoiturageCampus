<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultation</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-top: 60px;
            background-color: #ffffff; /* Fond blanc */
            color: #000000; /* Texte noir pour contraste */
            margin: 0;
        }

        header {
            width: 100%;
            position: fixed;
            top: 0;
            background-color: #333; /* En-tête en gris foncé */
            color: white;
            padding: 20px 0;
            text-align: center;
            z-index: 1000;
        }

        h2, h3 {
            text-align: center;
            color: #333;
        }

        .tableContainer {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #333; /* En-têtes de table en gris foncé */
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        button {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #555;
        }

        .form-section input, .form-section button {
            padding: 10px;
            margin: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        @media screen and (max-width: 600px) {
            .form-section input, .form-section button {
                width: calc(100% - 20px);
            }
        }
        #dualTableContainer {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); /* Créez autant de colonnes que possible avec un minimum de 400px */
            gap: 20px; /* Espace entre les colonnes */
            padding: 20px;
            max-width: 1200px; /* Ou la largeur que vous préférez */
            margin: auto; /* Centrer le conteneur */
        }

        /* Style spécifique pour les tableaux dans le conteneur de grille */
        #dualTableContainer #tableContainer {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin: 0; /* Annuler les marges pour les éléments de grille */
        }

        
    </style>

</head>
<body>
    <?php include('header.php'); ?>

    <div id="dualTableContainer">
    <div id="tableContainer">
        <h2>All the information on Drivers </h2>
        <?php
            include '../connect.php';
            $query = "SELECT * FROM ETUDIANT
            WHERE NUM_ETUDIANT IN (SELECT NUM_ETUDIANT FROM ETUDIANT
                                    INTERSECT
                                    SELECT NUM_CONDUCTEUR FROM CONDUCTEUR);";
            if ($result = $conn->query($query)) {
                echo '<table>';
            echo '<tr><th>Student number</th><th>FirstName</th><th>LastName</th></tr>';
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row["NUM_ETUDIANT"] . '</td>';
                echo '<td>' . $row["PRENOM"] . '</td>';
                echo '<td>' . $row["NOM"] . '</td>';
                echo '</tr>';
            }
            echo '</table>';
            } else {
                echo '<div id="result"><h3>Erreur :</h3>';
                echo '<p>' . $conn->error . '</p>';
                echo '</div>';
            }
            ?>
    </div>
    
    <div id="tableContainer">
        <h2>All the information on Passengers</h2>
        <?php
            $query = "SELECT * FROM ETUDIANT
            WHERE NUM_ETUDIANT IN (SELECT NUM_ETUDIANT FROM ETUDIANT
                                    INTERSECT
                                    SELECT NUM_PASSAGER FROM PASSAGER);";
            if ($result = $conn->query($query)) {
                echo '<table>';
            echo '<tr><th>Student number</th><th>FirstName</th><th>LastName</th></tr>';
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row["NUM_ETUDIANT"] . '</td>';
                echo '<td>' . $row["PRENOM"] . '</td>';
                echo '<td>' . $row["NOM"] . '</td>';
                echo '</tr>';
            }
            echo '</table>';
            } else {
                echo '<div id="result"><h3>Erreur :</h3>';
                echo '<p>' . $conn->error . '</p>';
                echo '</div>';
            }
            ?>
    </div>
</div>
    

<div id="dualTableContainer">
    <div id="tableContainer">
    <?php
    include '../connect.php'; 

    
    $sql = "SELECT ville, COUNT(*) AS nombre_trajets
            FROM (
                SELECT ADRESSE AS ville FROM ESCALE
                UNION ALL
                SELECT ADRESSE_ARRIVEE AS ville FROM TRAJET
            ) AS villes
            GROUP BY ville
            ORDER BY nombre_trajets DESC";
    echo '<tr> Classification of cities by trips</tr>';
    // Exécution de la requête
    if ($result = $conn->query($sql)) {
        echo '<table>';
        echo '<tr><th>City</th><th>Number of trips</th></tr>';
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row["ville"] . '</td>';
            echo '<td>' . $row["nombre_trajets"] . '</td>';
            echo '</tr>';
        }
        echo '</table>';

        // Libération des résultats
        $result->free();
    }
    ?>
</div>

<div id="tableContainer">
<?php
        include('../connect.php');

        $query = "SELECT e.NOM, e.PRENOM, eval.NOTE AS avis
        FROM CONDUCTEUR c
        JOIN ETUDIANT e ON c.NUM_CONDUCTEUR = e.NUM_ETUDIANT
        JOIN EVALUATION eval ON eval.NUM_ETUDIANT_EVALUE = c.NUM_CONDUCTEUR
        ORDER BY eval.note DESC;";

        if ($result = $conn->query($query)) {
            
            echo '<table>';
            echo '<tr> The ranking of the best ranked drivers</tr>';
            echo '<tr><th>LastName</th><th>FirstName</th><th>rating</th></tr>';
            while($row = $result->fetch_assoc()){
                echo '<tr>';
                echo '<td>' . $row['NOM'] . '</td>';
                echo '<td>' . $row['PRENOM'] . '</td>';
                echo '<td>' . $row['avis'] . '</td>';
                echo '</tr>';
        }
        } else {
            echo '<div id="result"><h3>Erreur :</h3>';
            echo '<p>' . $conn->error . '</p>';
            echo '</div>';
        }
        echo '</table>';
        $conn->close();
    ?>
</div>

    
</div>



</body>
</html>
