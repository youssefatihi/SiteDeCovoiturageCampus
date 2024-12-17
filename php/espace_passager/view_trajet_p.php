<!DOCTYPE html>
<html>
<head>
    <title>Driver Space - Manage Car and Rides</title>
    <!-- Add your CSS or external stylesheet links here -->
    <link rel="stylesheet" type="text/css" href="../../css/esp_conducteur.css">
    <link rel="stylesheet" type="text/css" href="../../css/table_style.css">
</head>
<body>
    <?php include('../header.php'); ?>

    <div class="container">
        <?php include('nav_bar.php'); ?>
        <div class="main-content" id="mainContent">
            <!-- Your content related to managing cars and rides goes here -->
            <h2>Welcome to the Driver Space</h2>

            <?php
            include('../../connect.php');
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);

            // Check if num_trajet is set and not empty
            if (isset($_GET['num_trajet']) && !empty($_GET['num_trajet'])) {
                // Sanitize the input to prevent SQL injection
                $num_trajet = $_GET['num_trajet'];

                // Perform a database query based on the NUM_TRAJET
                $query1 = "SELECT * FROM TRAJET WHERE NUM_TRAJET = ?";
                $stmt1 = $conn->prepare($query1);
                $stmt1->bind_param("i", $num_trajet);

                if ($stmt1->execute()) {
                    $result1 = $stmt1->get_result();

                    if ($result1->num_rows > 0) {
                        echo '<div style="overflow-x:auto;">'; // Add a container for horizontal scrolling
                        echo '<table>';
                        $columns = ['NUM_TRAJET', 'NUM_IMMATRICULE', 'DATE_DEPART', 'DATE_ARRIVEE', 'ADRESSE_ARRIVEE', 'CODE_POSTAL', 'NBR_ESCALES', 'PRIX_KILOMETRAGE', 'DISTANCE_TOTAL', 'DUREE_ESTIME'];
                        while ($row = $result1->fetch_assoc()) {
                            foreach ($columns as $column) {
                                echo '<tr>'; // Start new table row
                                echo "<th>$column</th>";
                                echo "<td>{$row[$column]}</td>"; // Display other columns vertically
                                echo '</tr>';
                            }
                        }
                        echo '</table>';
                        echo '</div>'; // Close the container for horizontal scrolling

                        $query2 = "SELECT E.* FROM ETUDIANT E
                        JOIN CONDUCTEUR C ON C.NUM_CONDUCTEUR=E.NUM_ETUDIANT
                        JOIN VOITURE V ON V.NUM_CONDUCTEUR=C.NUM_CONDUCTEUR
                        JOIN  TRAJET T ON T.NUM_IMMATRICULE=V.NUM_IMMATRICULE WHERE NUM_TRAJET = ?";
                        $stmt2 = $conn->prepare($query2);
                        $stmt2->bind_param("i", $num_trajet);

                        if ($stmt2->execute()) {
                            $result2 = $stmt2->get_result();
                            echo "<h2>Your driver is</h2>";
                            if ($result2->num_rows > 0) {
                                echo '<div style="overflow-x:auto;">'; // Add a container for horizontal scrolling
                                echo '<table>';
                                $columns = ['NUM_ETUDIANT', 'NOM', 'PRENOM'];
                                while ($row = $result2->fetch_assoc()) {
                                    foreach ($columns as $column) {
                                        echo '<tr>'; // Start new table row
                                        echo "<th>$column</th>";
                                        echo "<td>{$row[$column]}</td>"; // Display other columns vertically
                                        echo '</tr>';
                                    }
                                }
                                echo '</table>';
                                echo '</div>'; // Close the container for horizontal scrolling
                            } else {
                                echo "No result found";
                            }
                        } else {
                            echo "Error executing query 2: " . $stmt2->error;
                        }
                        
                        // Close statement
                        $stmt2->close();
                    } else {
                        echo "No result found";
                    }
                } else {
                    echo "Error executing query 1: " . $stmt1->error;
                }

                // Close statement
                $stmt1->close();
            }
            ?>
        </div>
    </div>

    <?php include('../footer.php'); ?>
</body>
</html>
