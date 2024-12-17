
<!DOCTYPE html>
<html>
<head>
    <title>Driver Space - Manage Car and Rides</title>
    <!-- Add your CSS or external stylesheet links here -->
    <link rel="stylesheet" type="text/css" href="../../css/table_style.css">
</head>
<?php
include('../../connect.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    // Check if num_etudiant is set and not empty
    if (isset($_GET['num_etudiant']) && !empty($_GET['num_etudiant'])) {
        // Sanitize the input to prevent SQL injection
        $num_etudiant = $_GET['num_etudiant'];

        // Perform a database query based on the student number using a prepared statement
        $query1 = "SELECT E.NUM_ETUDIANT, E.NOM, E.PRENOM, T.NUM_TRAJET, R.VALIDATION_RESERVATION, AVG(EV.NOTE) AS NOTE, T.DATE_ARRIVEE 
                    FROM ETUDIANT E 
                    JOIN PASSAGER P ON P.NUM_PASSAGER = E.NUM_ETUDIANT
                    JOIN RESERVATION R ON R.NUM_PASSAGER = P.NUM_PASSAGER
                    JOIN TRAJET T ON T.NUM_TRAJET = R.NUM_TRAJET
                    JOIN VOITURE V ON V.NUM_IMMATRICULE = T.NUM_IMMATRICULE
                    LEFT JOIN EVALUATION EV ON EV.NUM_ETUDIANT_EVALUE = P.NUM_PASSAGER
                    WHERE V.NUM_CONDUCTEUR = ?
                    GROUP BY E.NUM_ETUDIANT, E.NOM, E.PRENOM
                    ORDER BY EV.NOTE DESC";

        $stmt1 = $conn->prepare($query1);
        $stmt1->bind_param("i", $num_etudiant);
        $stmt1->execute();
        $result1 = $stmt1->get_result();

        if ($result1->num_rows > 0) {
            echo '<table>';
            echo '<tr>';
            $columns = ['NOM', 'PRENOM', 'NUM_TRAJET', 'NOTE', 'ACTION'];
            foreach ($columns as $column) {
                echo "<th>$column</th>";
            }
            echo '</tr>';
            while ($row = $result1->fetch_assoc()) {
                echo '<tr>'; // Start new table row
                foreach ($columns as $column) {
                    if ($column !== 'ACTION') {
                        if ($column === 'NUM_TRAJET') {
                            echo "<td><a href='view_trajet.php?num_trajet={$row[$column]}'>{$row[$column]}</a></td>";
                        } else {
                            echo "<td>{$row[$column]}</td>"; // Display other columns normally
                        }
                    } else {
                        // Check the validation status and set button text accordingly
                        $validation_status = $row['VALIDATION_RESERVATION'];
                        $button_text = '';
                        $action_link = 'valider_resa.php';

                        if (strtotime($row['DATE_ARRIVEE']) <= time() && $validation_status==1) {
                            // Change the button text to "Evaluer" and redirect to ".php"
                            $button_text = 'Evaluer';
                            $action_link = 'fetch_evaluation_passager.php';
                        } else {
                            $button_text = ($validation_status == 1) ? 'Cancel' : 'Confirm';
                        }

                        echo '<td><form method="post" action="' . $action_link . '">';
                        echo '<input type="hidden" name="num_passager" value="' . $row['NUM_ETUDIANT'] . '">';
                        echo '<input type="hidden" name="num_trajet" value="' . $row['NUM_TRAJET'] . '">';
                        echo '<input type="hidden" name="num_conducteur" value="' . $num_etudiant . '">';

                        // Display the button with the appropriate text
                        echo "<input type='submit' class='confirm-button' value='$button_text'></form></td>";
                    }
                }
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo "<p>No reservation requests found<p>";
        }

        // Close statement and connection
        $stmt1->close();
        $conn->close();
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>



</body>
</html>