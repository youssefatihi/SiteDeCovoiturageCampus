<?php
include('../../connect.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if num_immatricule is set and not empty
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['num_immatricule'])) {
    // Sanitize the input to prevent SQL injection
    $num_immatricule = $_POST['num_immatricule'];
    
    // Prompt a confirmation before deletion
    $confirm_delete = isset($_POST['confirm_delete']) ? $_POST['confirm_delete'] : null;

    if ($confirm_delete === 'yes') {
        // Perform a database query based on the car number
        $query1 = "DELETE FROM VOITURE WHERE NUM_IMMATRICULE = ?";
        $stmt1 = $conn->prepare($query1);
        $stmt1->bind_param("i", $num_immatricule);
        
        if ($stmt1->execute()) {
            header("Refresh:1; url=info_vehicule.php");
            echo "Car correctly removed. Please note, all information relating to this car has been deleted.";
        } else {
            echo "Error executing query 1";
        }
        // Close statement 1
        $stmt1->close();
    } else if ($confirm_delete === 'no') {
        header("Refresh:1; url=info_vehicule.php");
        // Backtrack or handle the situation if user chooses "No"
        echo "Deletion canceled. The car has not been deleted.";
    } else {
        // Display a confirmation message
        echo '<form method="post" action="">
            <input type="hidden" name="num_immatricule" value="' . $num_immatricule . '">
            <input type="hidden" name="confirm_delete" value="yes">
            <p>Are you sure you want to delete this car? All related information will also be deleted.</p>
            <button type="submit">Yes, Delete</button>
            <button type="submit" name="confirm_delete" value="no">No, Cancel</button>
        </form>';
    }
}
?>
