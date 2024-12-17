<!DOCTYPE html>
<html>
<head>
    <title>Driver Space - Manage Car and Rides</title>
    <!-- Add your CSS or external stylesheet links here -->
    <link rel="stylesheet" type="text/css" href="../../css/table_style.css">
</head>
<body>
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
        $query1 = "SELECT T.NUM_TRAJET, T.DATE_DEPART FROM TRAJET T 
                    JOIN RESERVATION R ON R.NUM_TRAJET=T.NUM_TRAJET
                    WHERE R.NUM_PASSAGER=?";

        $stmt1 = $conn->prepare($query1);
        $stmt1->bind_param("i", $num_etudiant);
        $stmt1->execute();
        $result1 = $stmt1->get_result();

        if ($result1->num_rows > 0) {
            echo '<table>';
            echo '<tr>';
            $columns = ['NUM_TRAJET', 'ACTION'];
            foreach ($columns as $column) {
                echo "<th>$column</th>";
            }
            echo '</tr>';
            while ($row = $result1->fetch_assoc()) {
                echo '<tr>'; // Start new table row
                foreach ($columns as $column) {
                    if ($column !== 'ACTION') {
                        if ($column === 'NUM_TRAJET') {
                            echo "<td><a href='view_trajet_p.php?num_trajet={$row[$column]}'>{$row[$column]}</a></td>";
                        } else {
                            echo "<td>{$row[$column]}</td>"; // Display other columns normally
                        }
                    } else {
                        $button_text = '';
                        $action_link = '';

                        if (strtotime($row['DATE_DEPART']) > time()) {
                            // Display cancel button if DATE_DEPART is in the future
                            $button_text = 'Cancel';
                            $action_link = 'cancel_reservation.php'; // Update with your cancel reservation action link
                        }

                        echo '<td>';
                        if (!empty($button_text)) {
                            echo "<form method='post' action='$action_link'>";
                            echo "<input type='hidden' name='num_passager' value='" . $num_etudiant . "'>";
                            echo "<input type='hidden' name='num_trajet' value='" . $row['NUM_TRAJET'] . "'>";
                            echo "<input type='submit' class='cancel-button' value='$button_text'>";
                            echo '</form>';
                        }
                        echo '</td>';
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
