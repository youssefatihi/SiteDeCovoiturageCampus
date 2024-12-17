<?php
// Include your database connection file
include('../../connect.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve parameters from the form
        $num_passager = $_POST['num_passager'];
        $num_trajet = $_POST['num_trajet'];

        // Perform cancellation logic or update the database
        // Write your SQL queries here to cancel the reservation based on the given parameters
        // For example:
        $query = "DELETE FROM RESERVATION WHERE NUM_PASSAGER = ? AND NUM_TRAJET = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $num_passager, $num_trajet);
        $stmt->execute();

        // Check if the query was successful
        if ($stmt->affected_rows > 0) {
            echo "Reservation cancelled successfully.";
        } else {
            echo "Failed to cancel the reservation.";
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();
    } else {
        echo "Invalid request method.";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

