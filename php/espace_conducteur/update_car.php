<?php
include('../../connect.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['columnName'], $_POST['newValue'], $_POST['numImmatricule'])) {
        $columnName = $_POST['columnName'];
        $newValue = $_POST['newValue'];
        $numImmatricule = $_POST['numImmatricule'];

        // Perform the update query
        $query = "UPDATE VOITURE SET $columnName = ? WHERE NUM_IMMATRICULE = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $newValue, $numImmatricule);

        if ($stmt->execute()) {
            header("Refresh:1; url=info_vehicule.php");
            // Update successful
            echo "Update successful";
        } else {
            // Update failed
            echo "Update failed";
        }

        $stmt->close();
    }
}
$conn->close();
?>
