<?php
include('../../connect.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if num_passager, num_trajet, and validation_status are set and not empty
if (
    isset($_POST['num_passager'], $_POST['num_trajet']) &&
    !empty($_POST['num_passager']) && !empty($_POST['num_trajet'])
) {
    try {
        // Sanitize the input to prevent SQL injection
        $num_passager = $_POST['num_passager'];
        $num_trajet = $_POST['num_trajet'];

        // Retrieve the current validation status from the database
        $query1 = "SELECT VALIDATION_RESERVATION FROM RESERVATION WHERE NUM_PASSAGER = ? AND NUM_TRAJET = ?";
        $stmt1 = $conn->prepare($query1);
        $stmt1->bind_param("ii", $num_passager, $num_trajet);
        $stmt1->execute();
        $result1 = $stmt1->get_result();

        if ($result1->num_rows > 0) {
            $row = $result1->fetch_assoc();
            $validation_status = $row['VALIDATION_RESERVATION'];

            // Invert the validation status
            $new_validation_status = ($validation_status == 1) ? 0 : 1;

            // Prepare and execute the SQL update query
            $query = "UPDATE RESERVATION SET VALIDATION_RESERVATION = ? WHERE NUM_PASSAGER = ? AND NUM_TRAJET = ?";
            $stmt2 = $conn->prepare($query);
            $stmt2->bind_param("iii", $new_validation_status, $num_passager, $num_trajet);

            if ($stmt2->execute()) {
                header("Refresh:1; url=demandes_resa.php");
                echo "Booking updated successfully.";
            } else {
                echo "Booking update failed.";
            }

            // Close statement
            $stmt2->close();
        } else {
            echo "No reservation found for given passenger and trip.";
        }

        // Close statement and connection
        $stmt1->close();
        $conn->close();
    } catch (Exception $e) {
        header("Refresh:1; url=demandes_resa.php");
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
} else {
    echo "Missing or invalid parameters for validation.";
}
?>
