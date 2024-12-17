
<?php
include('../../connect.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if num_etudiant is set and not empty
if (isset($_GET['num_etudiant']) && !empty($_GET['num_etudiant'])) {
    // Sanitize the input to prevent SQL injection
    $num_etudiant = $_GET['num_etudiant'];
    
    // Perform a database query based on the student number
    $query1 = "SELECT * FROM CONDUCTEUR C WHERE NUM_CONDUCTEUR = ?";
    $stmt1 = $conn->prepare($query1);
    $stmt1->bind_param("i", $num_etudiant);

    if ($stmt1->execute()) {
        $result1 = $stmt1->get_result();
        if ($result1->num_rows == 0) {
            echo "You do not yet have a vehicle entered! Join the adventure by entering your vehicle.";
            echo '<h2>Add Your Car</h2>
            <form id="carForm" method="post" action="insert_car.php?num_etudiant=' . $num_etudiant . '">
                <div class="form-group">
                    <label for="num_immatricule">Plate number:</label>
                    <input type="text" id="num_immatricule" name="num_immatricule" placeholder="Entrer numéro matricule " required>
                </div>
                <div class="form-group">
                    <label for="type_voiture">Vehicle model:</label>
                    <input type="text" id="type_voiture" name="type_voiture" placeholder="Entrer le type du véhicule" required>
                </div>
                <div class="form-group">
                    <label for="etat">Vehicle state</label>
                    <input type="text" id="etat" name="etat" placeholder="Etat du véhicule" required>
                </div>
                <div class="form-group">
                    <label for="couleur">Vehicle color:</label>
                    <input type="text" id="couleur" name="couleur" placeholder="Couleur du véhicule" required>
                </div>
                <div class="form-group">
                    <label for="nbre_passagers">Vehicle capacity:</label>
                    <input type="text" id="nbr_passager" name="nbr_passager" placeholder="Capacité du véhicule" required>
                </div>
                <div class="form-group">
                    <input type="submit" value="Add Car">
                </div>
            </form>';
        } else {
            echo "Information about your vehicle";
            $query2 = "SELECT NUM_IMMATRICULE, TYPE_VOITURE, ETAT, COULEUR, NBR_PASSAGER FROM VOITURE WHERE NUM_CONDUCTEUR = ?";
            $stmt2 = $conn->prepare($query2);
            $stmt2->bind_param("i", $num_etudiant);

            if ($stmt2->execute()) {
                $result2 = $stmt2->get_result();
                if ($result2->num_rows > 0) {
                    echo '<table>';
                    echo '<tr>';
                    $columns = ['NUM_IMMATRICULE', 'TYPE_VOITURE', 'ETAT', 'COULEUR', 'NBR_PASSAGER', 'ACTION'];
                    foreach ($columns as $column) {
                        echo "<th>$column</th>";
                    }
                    echo '</tr>';
                    while ($row = $result2->fetch_assoc()) {
                        echo '<tr>'; // Start new table row
                        foreach ($columns as $column) {
                            if ($column !== 'ACTION') {
                                echo '<td onclick="editCell(this, \'' . $column . '\', \'' . $row['NUM_IMMATRICULE'] . '\')">' . $row[$column] . '</td>';
                            } else {
                                echo '<td><form method="post" action="delete_car.php">';
                                echo '<input type="hidden" name="num_immatricule" value="' . $row['NUM_IMMATRICULE'] . '">';
                                echo '<input type="submit" class="delete-button" value="Delete"></form></td>';                                                               
                            }
                        }                        
                        echo '</tr>';
                    }
                    echo '</table>';
                } else {
                    echo "No result found";
                }
            } else {
                echo "Error executing query 2";
            }
            $stmt2->close();
        }
    } else {
        echo "Error executing query 2";
    }
    // Close statement 1
    $stmt1->close();
}
$conn->close();
?>


    

