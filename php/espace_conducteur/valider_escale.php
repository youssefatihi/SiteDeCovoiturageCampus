<?php
include('../../connect.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if num_passager, num_escale, and validation_status are set and not empty
if (
    isset($_POST['num_passager'], $_POST['num_escale']) &&
    !empty($_POST['num_passager']) && !empty($_POST['num_escale'])
) {
    // Sanitize the input to prevent SQL injection
    $num_passager = $_POST['num_passager'];
    $num_escale = $_POST['num_escale'];

    try {
        // Begin a transaction
        $conn->begin_transaction();

        // Retrieve the current validation status from the database
        $query1 = "SELECT VALIDATION_ESCALE FROM ESCALE E
                    JOIN PROPOSITION P ON P.NUM_ESCALE = E.NUM_ESCALE  
                    WHERE P.NUM_PASSAGER = ? AND P.NUM_ESCALE = ?";
        $stmt1 = $conn->prepare($query1);
        $stmt1->bind_param("ii", $num_passager, $num_escale);
        $stmt1->execute();
        $result1 = $stmt1->get_result();

        if ($result1->num_rows > 0) {
            $row = $result1->fetch_assoc();
            $validation_status = $row['VALIDATION_ESCALE'];

            // Invert the validation status
            $new_validation_status = ($validation_status == 1) ? 0 : 1;

            // Prepare and execute the SQL update query
            $query = "UPDATE ESCALE SET VALIDATION_ESCALE = ? WHERE NUM_ESCALE = ?";
            $stmt2 = $conn->prepare($query);
            $stmt2->bind_param("ii", $new_validation_status, $num_escale);

            if ($stmt2->execute()) {
                // Commit the transaction if all queries are successful
                $conn->commit();
                header("Refresh:1; url=prop_escale.php");
                echo "Booking updated successfully.";
            } else {
                echo "Booking update failed.";
            }

            // Close statement
            $stmt2->close();
        } else {
            echo "No ESCALE found for given passager and trajet.";
        }

        // Close statement and connection
        $stmt1->close();
        $conn->close();
    } catch (Exception $e) {
        // Roll back the transaction if any query fails
        $conn->rollback();
        echo "Transaction failed: " . $e->getMessage();
    }
} else {
    echo "Missing or invalid parameters for validation.";
}
?>

